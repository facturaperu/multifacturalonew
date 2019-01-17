<?php

namespace App\CoreFacturalo\Core;

use App\CoreFacturalo\Documents\DispatchBuilder;
use App\CoreFacturalo\Documents\InvoiceBuilder;
use App\CoreFacturalo\Documents\NoteBuilder;
use App\CoreFacturalo\Documents\RetentionBuilder;
use App\CoreFacturalo\Documents\SummaryBuilder;
use App\CoreFacturalo\Documents\VoidedBuilder;
use App\CoreFacturalo\Helpers\QrCode\QrCodeGenerate;
use App\CoreFacturalo\Helpers\Xml\XmlFormat;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Helpers\Xml\XmlHash;
use App\CoreFacturalo\Templates\Template;
use App\CoreFacturalo\WS\Client\WsClient;
use App\CoreFacturalo\WS\Services\BillSender;
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
use Mpdf\Mpdf;

class FacturaloCore
{
//    use StorageDocument;

    const REGISTERED = '01';
    const SENT = '03';
    const ACCEPTED = '05';
    const OBSERVED = '07';
    const REJECTED = '09';
    const ANNULLED = '11';
    const PENDING = '13';

    protected $signer;
    protected $wsClient;
    protected $inputs;
    protected $type;
    protected $actions;
    protected $filename;
    protected $company;
    protected $isDemo;
    protected $document_id;
    protected $document;
    protected $xmlUnsigned;
    protected $xmlSigned;
    protected $response = [];
    protected $pathCertificate;
    protected $soapUsername;
    protected $soapPassword;
    protected $endpoint;

    public function __construct()
    {
        $this->company = Company::active();
        $this->isDemo = ($this->company->soap_type_id === '01')?true:false;
        $this->signer = new XmlSigned();
        $this->wsClient = new WsClient();
    }

    public function setInputs($inputs)
    {
        $this->inputs = $inputs;
        $this->type = $inputs['type'];
        $this->actions = $inputs['actions'];
        $this->filename = $inputs['filename'];
    }

//    public function setType($type)
//    {
//        $this->type = $type;
//    }
//
    public function getType()
    {
        return $this->type;
    }

    public function getInputs()
    {
        return $this->inputs;
    }

    public function getDocument()
    {
        return $this->document;
    }
//
//    public function setDocument($document)
//    {
//        $this->document = $document;
//    }
//
//    public function getDocument()
//    {
//        return $this->document;
//    }

//    public function setXmlUnsigned($xmlUnsigned)
//    {
//        $this->xmlUnsigned = $xmlUnsigned;
//    }
//
//    public function getXmlUnSigned()
//    {
//        return $this->xmlUnsigned;
//    }
//
//    public function setXmlSigned($xmlSigned)
//    {
//        $this->xmlSigned = $xmlSigned;
//    }
//
//    public function getXmlSigned()
//    {
//        return $this->xmlSigned;
//    }
//
//    public function setResponse($response)
//    {
//        $this->response = $response;
//    }
//
//    public function getResponse()
//    {
//        return $this->response;
//    }

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

    public function loadXmlSigned()
    {
        $this->xmlSigned = $this->getStorage($this->filename, 'signed');
    }

    public function senderXmlSigned()
    {
        $this->setDataSoapType();
        $sender = in_array($this->type, ['summary', 'voided'])?new SummarySender():new BillSender();
        $sender->setClient($this->wsClient);
        $sender->setCodeProvider(new XmlErrorCodeProvider());

        return $sender->send($this->filename, $this->xmlSigned);
    }

    public function createPdf()
    {
        $template = new Template();
        $html = $template->pdf($this->type, $this->company, $this->document, $this->actions['format_pdf']);

        $pdf = new Mpdf();
        $pdf->WriteHTML($html);
        $this->uploadFile($pdf->output('', 'S'), 'pdf');
    }

    public function uploadFile($file_content, $file_type)
    {
        StorageDocument::upload($this->filename, $file_type, $file_content);
    }

    private function setDataSoapType()
    {
        $this->setSoapCredentials();

        $this->wsClient->setCredentials($this->soapUsername, $this->soapPassword);
        $this->wsClient->setService($this->endpoint);
    }

    public function save()
    {
        switch ($this->type) {
            case 'debit':
            case 'credit':
                $builder = new NoteBuilder();
                break;
            case 'summary':
                $builder = new SummaryBuilder();
                break;
            case 'voided':
                $builder = new VoidedBuilder();
                break;
            case 'retention':
                $builder = new RetentionBuilder();
                break;
            case 'dispatch':
                $builder = new DispatchBuilder();
                break;
            default:
                $builder = new InvoiceBuilder();
                break;
        }

        $this->document_id = $builder->save($this->inputs);
        $this->setDocument();
    }

    private function setDocument()
    {
        switch ($this->type) {
            case 'summary':
                $this->document = Summary::find($this->document_id);
                break;
            case 'voided':
                $this->document = Voided::find($this->document_id);
                break;
            case 'retention':
                $this->document = Retention::find($this->document_id);
                break;
            case 'dispatch':
                $this->document = Dispatch::find($this->document_id);
                break;
            default:
                $this->document = Document::find($this->document_id);
                break;
        }
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
            $this->document->date_of_issue,
            $customer->identity_document_type_id,
            $customer->number,
            $this->document->hash
        ]);

        $qrCode = new QrCodeGenerate();
        $qr = $qrCode->displayPNGBase64($text);

        return $qr;
    }

    public function updateStateType($state_type_id)
    {
        $this->document->update([
            'state_type_id' => $state_type_id
        ]);
    }

    public function updateTicket($ticket)
    {
        $this->document->update([
            'ticket' => $ticket,
            'has_ticket' => true
        ]);
    }

    public function updateStateTypeDocuments($state_type_id)
    {
        $documents = $this->document->details;
        foreach ($documents as $doc)
        {
            $doc->document->update([
                'state_type_id' => $state_type_id
            ]);
        }
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
