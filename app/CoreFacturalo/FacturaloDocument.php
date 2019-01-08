<?php

namespace App\CoreFacturalo;

use App\CoreFacturalo\Helpers\QrCode\QrCodeGenerate;
use App\CoreFacturalo\Helpers\Xml\XmlHash;
use Exception;

class FacturaloDocument extends FacturaloCore
{
    public function createXmlAndSign()
    {
        $this->createXmlUnsigned();
        $this->signXmlUnsigned();
        $this->updateHashAndQr();
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
            return [
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }
    }

    private function updateHashAndQr()
    {
        $hash = $this->getHash($this->xmlSigned);
        $this->document->update([
            'hash' => $hash,
            'qr' => $this->getQr($hash)
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

    private function getQr($hash)
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
            $hash
        ]);

        $qrCode = new QrCodeGenerate();
        $qr = $qrCode->displayPNGBase64($text);
        return $qr;
    }
}
