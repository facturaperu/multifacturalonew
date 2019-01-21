<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Helpers\QrCode\QrCodeGenerate;
use App\CoreFacturalo\Helpers\Xml\XmlFormat;
use App\CoreFacturalo\Helpers\Xml\XmlHash;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\WS\Client\WsClient;
use App\CoreFacturalo\WS\Services\BillSender;
use App\CoreFacturalo\WS\Services\ExtService;
use App\CoreFacturalo\WS\Services\SummarySender;
use App\CoreFacturalo\WS\Services\SunatEndpoints;
use App\CoreFacturalo\WS\Signed\XmlSigned;
use App\CoreFacturalo\WS\Validator\XmlErrorCodeProvider;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\Document;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Exception;
use Mpdf\Mpdf;

class Facturalo
{
    use StorageDocument;

    protected $company;
    protected $isDemo;
    protected $signer;
    protected $wsClient;
    protected $document;
    protected $type;
    protected $actions;
    protected $xmlUnsigned;
    protected $xmlSigned;
    protected $pathCertificate;
    protected $soapUsername;
    protected $soapPassword;
    protected $endpoint;
    protected $response;

    public function __construct()
    {
        $this->company = Company::active();
        $this->isDemo = ($this->company->soap_type_id === '01')?true:false;
        $this->signer = new XmlSigned();
        $this->wsClient = new WsClient();
        $this->setDataSoapType();
    }

    public function setDocument($document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function save($inputs)
    {
        $this->actions = $inputs['actions'];
        $this->type = $inputs['type'];
        switch ($this->type) {
            case 'debit':
            case 'credit':
                $document = Document::create($inputs);
                foreach ($inputs['details'] as $row) {
                    $document->items()->create($row);
                }
                $document->note()->create($inputs['note']);
                $this->document = Document::find($document->id);
                break;
            case 'invoice':
                $document = Document::create($inputs);
                foreach ($inputs['details'] as $row) {
                    $document->details()->create($row);
                }
                $document->invoice()->create($inputs['invoice']);
                $this->document = Document::find($document->id);
                break;
            case 'summary':
                $document = Summary::create($inputs);
                foreach ($inputs['documents'] as $row) {
                    $document->documents()->create($row);
                }
                $this->document = Summary::find($document->id);
                break;
            case 'voided':
                $document = Voided::create($inputs);
                foreach ($inputs['documents'] as $row) {
                    $document->documents()->create($row);
                }
                $this->document = Voided::find($document->id);
                break;
            case 'retention':
                $document = Retention::create($inputs);
                foreach ($inputs['details'] as $row) {
                    $document->documents()->create($row);
                }
                $this->document = Retention::find($document->id);
                break;
            default:
                $document = Dispatch::create($inputs);
                foreach ($inputs['details'] as $row) {
                    $document->items()->create($row);
                }
                $this->document = Dispatch::find($document->id);
                break;
        }
    }

    public function createXmlUnsigned()
    {
        $template = new Template();
        $this->xmlUnsigned = XmlFormat::format($template->xml($this->type, $this->company, $this->document));
        $this->uploadFile($this->xmlUnsigned, 'unsigned');
    }

    public function signXmlUnsigned()
    {
        $this->setPathCertificate();
        $this->signer->setCertificateFromFile($this->pathCertificate);
        $this->xmlSigned = $this->signer->signXml($this->xmlUnsigned);
        $this->uploadFile($this->xmlSigned, 'signed');
    }

    public function updateHash()
    {
        $this->document->update([
            'hash' => $this->getHash(),
        ]);
    }

    public function updateQr()
    {
        $this->document->update([
            'qr' => $this->getQr(),
        ]);
    }

    private function getHash()
    {
        $helper = new XmlHash();
        return $helper->getHashSign($this->xmlSigned);
    }

    private function getQr()
    {
        $customer = $this->document->customer;
        $text = join('|', [
            $this->company->number,
            $this->document->document_type_id,
            $this->document->series,
            $this->document->number,
            $this->document->total_igv,
            $this->document->total,
            $this->document->date_of_issue->format('Y-m-d'),
            $customer->identity_document_type_id,
            $customer->number,
            $this->document->hash
        ]);

        $qrCode = new QrCodeGenerate();
        $qr = $qrCode->displayPNGBase64($text);
        return $qr;
    }

    public function createPdf()
    {
        $template = new Template();
        $html = $template->pdf($this->type, $this->company, $this->document, $this->actions['format_pdf']);

        $pdf = new Mpdf();
        $pdf->WriteHTML($html);
        $this->uploadFile($pdf->output('', 'S'), 'pdf');
    }

    public function loadXmlSigned()
    {
        $this->xmlSigned = $this->getStorage($this->document->filename, 'signed');
    }

    private function senderXmlSigned()
    {
        $this->setDataSoapType();
        $sender = in_array($this->type, ['summary', 'voided'])?new SummarySender():new BillSender();
        $sender->setClient($this->wsClient);
        $sender->setCodeProvider(new XmlErrorCodeProvider());

        return $sender->send($this->document->filename, $this->xmlSigned);
    }

    public function senderXmlSignedBill()
    {
        $sent = true;
        if($this->actions['send_xml_signed']) {
            $sent = !($this->document->group_id === '02') && (bool)$this->actions['send_xml_signed'];
        }
        if(!$sent) {
            $this->response = [
                'sent' => false,
            ];
            return;
        }

        $res = $this->senderXmlSigned();
        if($res->isSuccess()) {
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            $this->response = [
                'sent' => true,
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        } else {
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        }
    }

    public function senderXmlSignedSummary()
    {
        $res = $this->senderXmlSigned();
        if($res->isSuccess()) {
            $ticket = $res->getTicket();
            $this->updateTicket($ticket);
            $this->response = [
                'sent' => true
            ];
        } else {
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        }
    }

    private function updateTicket($ticket)
    {
        $this->document->update([
            'ticket' => $ticket
        ]);
    }

    public function statusSummary($ticket)
    {
        $extService = new ExtService();
        $extService->setClient($this->wsClient);
        $extService->setCodeProvider(new XmlErrorCodeProvider());
        $res = $extService->getStatus($ticket);
        if(!$res->isSuccess()) {
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            $this->response = [
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }
    }

    public function uploadFile($file_content, $file_type)
    {
        $this->uploadStorage($this->document->filename, $file_content, $file_type);
    }

    private function setDataSoapType()
    {
        $this->setSoapCredentials();
        $this->wsClient->setCredentials($this->soapUsername, $this->soapPassword);
        $this->wsClient->setService($this->endpoint);
    }

    private function setPathCertificate()
    {
        if($this->isDemo) {
            $this->pathCertificate = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.
                'WS'.DIRECTORY_SEPARATOR.
                'Signed'.DIRECTORY_SEPARATOR.
                'Resources'.DIRECTORY_SEPARATOR.
                'certificate.pem');
        } else {
            $this->pathCertificate = storage_path('app'.DIRECTORY_SEPARATOR.
                'certificates'.$this->company->certificate);
        }
    }

    private function setSoapCredentials()
    {
        $this->soapUsername = ($this->isDemo)?'20000000000MODDATOS':$this->company->soap_username;
        $this->soapPassword = ($this->isDemo)?'moddatos':$this->company->soap_password;

        switch ($this->type) {
            case 'retention':
                $this->endpoint = ($this->isDemo)?SunatEndpoints::RETENCION_BETA:SunatEndpoints::RETENCION_PRODUCCION;
                break;
            case 'dispatch':
                $this->endpoint = ($this->isDemo)?SunatEndpoints::GUIA_BETA:SunatEndpoints::GUIA_PRODUCCION;
                break;
            default:
                $this->endpoint = ($this->isDemo)?SunatEndpoints::FE_BETA:SunatEndpoints::FE_PRODUCCION;
                break;
        }
    }
}
