<?php

namespace App\CoreFacturalo\Facturalo;

use App\CoreFacturalo\Documents\InvoiceBuilder;
use App\CoreFacturalo\Documents\NoteBuilder;
use App\CoreFacturalo\Documents\RetentionBuilder;
use App\CoreFacturalo\Documents\SummaryBuilder;
use App\CoreFacturalo\Documents\VoidedBuilder;
use App\CoreFacturalo\Helpers\Xml\XmlFormat;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Templates\Template;
use App\CoreFacturalo\WS\Client\WsClient;
use App\CoreFacturalo\WS\Services\BillSender;
use App\CoreFacturalo\WS\Services\SummarySender;
use App\CoreFacturalo\WS\Services\SunatEndpoints;
use App\CoreFacturalo\WS\Signed\XmlSigned;
use App\CoreFacturalo\WS\Validator\XmlErrorCodeProvider;
use App\Models\Tenant\Company;
use Mpdf\Mpdf;

class FacturaloCore
{
    use StorageDocument;

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
    protected $company;
    protected $isDemo;
    protected $type;
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
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setDocument($document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setXmlUnsigned($xmlUnsigned)
    {
        $this->xmlUnsigned = $xmlUnsigned;
    }

    public function getXmlUnSigned()
    {
        return $this->xmlUnsigned;
    }

    public function setXmlSigned($xmlSigned)
    {
        $this->xmlSigned = $xmlSigned;
    }

    public function getXmlSigned()
    {
        return $this->xmlSigned;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
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

    public function loadXmlSigned()
    {
        $this->xmlSigned = $this->getStorage($this->document->filename, 'signed');
    }

    public function senderXmlSigned()
    {
        $this->setDataSoapType();
        $sender = in_array($this->type, ['summary', 'voided'])?new SummarySender():new BillSender();
        $sender->setClient($this->wsClient);
        $sender->setCodeProvider(new XmlErrorCodeProvider());

        return $sender->send($this->document->filename, $this->xmlSigned);
    }

    public function createPdf()
    {
        $template = new Template();
        $html = $template->pdf($this->type, $this->company, $this->document);

        $pdf = new Mpdf();
        $pdf->WriteHTML($html);
        $this->uploadFile($pdf->output('', 'S'), 'pdf');
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
            default:
                $builder = new InvoiceBuilder();
                break;
        }

        $this->document = $builder->save($this->inputs);
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
            default:
                $this->endpoint = ($this->isDemo)?SunatEndpoints::FE_BETA:SunatEndpoints::FE_PRODUCCION;
                break;
        }
    }
}
