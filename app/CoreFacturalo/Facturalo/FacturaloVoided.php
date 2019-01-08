<?php

namespace App\CoreFacturalo\Facturalo;

use App\CoreFacturalo\WS\Services\ExtService;
use App\CoreFacturalo\WS\Validator\XmlErrorCodeProvider;
use Exception;

class FacturaloVoided extends FacturaloCore
{
    public function createXmlAndSign()
    {
        $this->createXmlUnsigned();
        $this->signXmlUnsigned();
    }

    public function sendXml()
    {
        $res = $this->senderXmlSigned();
        if(!$res->isSuccess()) {
            $this->updateStateType(self::REJECTED);
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $this->updateStateType(self::SENT);
            $this->updateStateTypeDocuments(self::PENDING);
            $this->updateTicket($res->getTicket());
        }
    }

    public function statusTicket()
    {
        $extService = new ExtService();
        $extService->setClient($this->wsClient);
        $extService->setCodeProvider(new XmlErrorCodeProvider());
        $res = $extService->getStatus($this->document->ticket);
        if(!$res->isSuccess()) {
            $this->updateStateType(self::REJECTED);
            $this->updateStateTypeDocuments(self::ACCEPTED);
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $this->updateStateType(self::ACCEPTED);
            $this->updateStateTypeDocuments(self::ANNULLED);
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            return [
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }
    }
}
