<?php

namespace App\CoreBuilder;

use App\CoreBuilder\Interfaces\BuilderInterface;
use App\CoreBuilder\Interfaces\DocumentInterface;
use App\CoreBuilder\Interfaces\SenderInterface;
use App\CoreBuilder\WS\Response\BaseResult;
use App\CoreBuilder\XmlDsig\Sunat\SignedXml;

class CpeFactory
{
    /**
     * @var SignedXml
     */
    private $signer;

    /**
     * Sender service.
     *
     * @var SenderInterface
     */
    private $sender;

    /**
     * Ultimo xml generado.
     *
     * @var string
     */
    private $lastXml;

    /**
     * Xml Builder.
     *
     * @var BuilderInterface
     */
    private $builder;

    /**
     * Get document builder.
     *
     * @return BuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Get sender service.
     *
     * @return SenderInterface
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set sender service.
     *
     * @param SenderInterface $sender
     *
     * @return CpeFactory
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set document builder.
     *
     * @param BuilderInterface $builder
     *
     * @return CpeFactory
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return SignedXml
     */
    public function getSigner()
    {
        return $this->signer;
    }

    /**
     * @param SignedXml $signer
     *
     * @return CpeFactory
     */
    public function setSigner($signer)
    {
        $this->signer = $signer;

        return $this;
    }

    /**
     * Build and send document.
     *
     * @param DocumentInterface $document
     *
     * @return BaseResult
     */
    public function send(DocumentInterface $document)
    {
        $xml = $this->getXmlSigned($document);

        return $this->sender->send($document->getName(), $xml);
    }


    public function sendXml($name, $xml)
    {
        return $this->sender->send($name, $xml);
    }
    
    /**
     * Get Last XML Signed.
     *
     * @return string
     */
    public function getLastXml()
    {
        return $this->lastXml;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return string
     */
    public function getXmlSigned(DocumentInterface $document)
    {
        $xml = $this->builder->build($document);

//        public_path(file_put_contents('prueba.xml', $xml));
//        dd($xml);

        $this->lastXml = $this->signer->signXml($xml);

        return $this->lastXml;
    }
}
