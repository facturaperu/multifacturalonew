<?php

namespace App\CoreFacturalo\Facturalo;

use App\CoreFacturalo\Helpers\Xml\XmlHash;
use Exception;

class FacturaloRetention extends FacturaloCore
{
    public function createXmlAndSign()
    {
        $this->createXmlUnsigned();
        $this->signXmlUnsigned();
        $this->updateHash();
    }

    public function sendXml()
    {
        $res = $this->senderXmlSigned();
        if(!$res->isSuccess()) {
            $this->updateStateType(self::REJECTED);
            throw new Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $this->updateStateType(self::ACCEPTED);
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            $this->updateHasPdfXmlCrd('cdr');

            $this->response = [
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }
    }

    private function updateHash()
    {
        $hash = $this->getHash($this->xmlSigned);
        $this->document->update([
            'hash' => $hash
        ]);
    }

    private function updateHasPdfXmlCrd($type)
    {
        $this->document->update([
            'has_'.$type => true,
        ]);
    }

    private function getHash($xmlContent)
    {
        $helper = new XmlHash();
        return $helper->getHashSign($xmlContent);
    }
}
