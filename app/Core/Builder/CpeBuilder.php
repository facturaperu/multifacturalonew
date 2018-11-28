<?php

namespace App\Core\Builder;

use App\Core\Helpers\StorageDocument;
use App\Core\WS\Client\SunatEndpoints;
use App\Core\WS\Client\WSClient;
use App\Core\WS\Services\BillSender;
use App\Core\WS\Services\ExtService;
use App\Core\WS\Services\SummarySender;
use App\Models\Tenant\Company;
use Exception;

class CpeBuilder
{
//    use StorageDocument;

    protected $wsClient;

    public function __construct($document)
    {
        $company = Company::first();
        if ($document->soap_type_id === '01') {
            $username = '20000000000MODDATOS';
            $password = 'moddatos';
            $this->wsClient = new WSClient();
            $wsdl = SunatEndpoints::FE_BETA;
        } else {
            $username = $company->soap_username;
            $password = $company->soap_password;
            $this->wsClient = new WSClient(app_path('Core'.DIRECTORY_SEPARATOR.'WS'.
                DIRECTORY_SEPARATOR.'Client'.
                DIRECTORY_SEPARATOR.'Wsdl'.
                DIRECTORY_SEPARATOR.'billService.wsdl'));
            $wsdl = SunatEndpoints::FE_PRODUCCION;
        }

        $this->wsClient->setCredentials($username, $password);
        $this->wsClient->setService($wsdl);
    }

    public function BillSender($filename, $xml_content)
    {
        $sender = new BillSender();
        $res = $sender->send($this->wsClient, $filename, $xml_content);
        return $res;
    }

    public function SummarySender($filename, $xml_content)
    {
        $sender = new SummarySender();
        $res = $sender->send($this->wsClient, $filename, $xml_content);
        return $res;
    }

    public function CheckTicket($ticket)
    {
        $extService = new ExtService();
        $res = $extService->getStatus($this->wsClient, $ticket);
        return $res;
    }

    public function checkCdrStatus($document)
    {
        $extService = new ExtService();
        $res = $extService->getCdrStatus($this->wsClient,
            $document->company->number,
            $document->document_type_code,
            $document->series,
            $document->number);
        return $res;
    }
}
