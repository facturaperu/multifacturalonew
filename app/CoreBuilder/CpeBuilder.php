<?php

namespace App\CoreBuilder;

use App\CoreBuilder\Documents\InvoiceBuilder;
use App\CoreBuilder\Interfaces\BuilderInterface;
use App\CoreBuilder\Interfaces\DocumentInterface;
use App\CoreBuilder\Interfaces\ErrorCodeProviderInterface;
use App\CoreBuilder\Interfaces\SenderInterface;
use App\CoreBuilder\WS\Response\BaseResult;
use App\CoreBuilder\WS\Response\StatusResult;
use App\CoreBuilder\WS\Services\BillSender;
use App\CoreBuilder\WS\Services\ExtService;
use App\CoreBuilder\WS\Services\SoapClient\WsSoapClient;
use App\CoreBuilder\WS\Services\SummarySender;
use App\CoreBuilder\Xml\Builder\InvoiceXmlBuilder;

//use App\Models\Tenant\Invoice;
use App\CoreBuilder\XmlDsig\Sunat\SignedXml;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;

class CpeBuilder
{
    /**
     * @var CpeBuilder
     */
    private $factory;

    /**
     * @var WsSoapClient
     */
    private $wsClient;

    /**
     * @var array
     */
    private $builders;

    /**
     * @var array
     */
    private $summaries;

    /**
     * @var SignedXml
     */
    private $signer;

    /**
     * @var ErrorCodeProviderInterface
     */
    private $codeProvider;

    /**
     * Twig Render Options.
     *
     * @var array
     */
    private $options = ['autoescape' => false];

    public function __construct()
    {
        $this->factory = new CpeFactory();
        $this->wsClient = new WsSoapClient();
        $this->signer = new SignedXml();
        $this->builders = [
            InvoiceBuilder::class => InvoiceXmlBuilder::class,
//            Model\Sale\Note::class => Xml\Builder\NoteBuilder::class,
//            Model\Summary\Summary::class => Xml\Builder\SummaryBuilder::class,
//            Model\Voided\Voided::class => Xml\Builder\VoidedBuilder::class,
//            Model\Despatch\Despatch::class => Xml\Builder\DespatchBuilder::class,
//            Model\Retention\Retention::class => Xml\Builder\RetentionBuilder::class,
//            Model\Perception\Perception::class => Xml\Builder\PerceptionBuilder::class,
//            Model\Voided\Reversion::class => Xml\Builder\VoidedBuilder::class,
        ];
        $this->summaries = [Summary::class, Summary::class, Voided::class];//, Reversion::class];
        $this->factory->setSigner($this->signer);
    }

    /**
     * Set Xml Builder Options.
     *
     * @param array $options
     */
    public function setBuilderOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param string $directory
     */
    public function setCachePath($directory)
    {
        $this->options['cache'] = $directory;
    }

    /**
     * @param string $certificate
     */
    public function setCertificate($certificate)
    {
        $this->signer->setCertificate($certificate);
    }

    /**
     * @param string $user
     * @param string $password
     */
    public function setCredentials($user, $password)
    {
        $this->wsClient->setCredentials($user, $password);
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        $this->wsClient->setService($service);
    }

    /**
     * Set error code provider.
     *
     * @param ErrorCodeProviderInterface $codeProvider
     */
    public function setCodeProvider($codeProvider)
    {
        $this->codeProvider = $codeProvider;
    }

    /**
     * Get signed xml from document.
     *
     * @param DocumentInterface $document
     *
     * @return string
     */
    public function getXmlSigned(DocumentInterface $document)
    {
        $classDoc = get_class($document);

        return $this->factory
            ->setBuilder($this->getBuilder($classDoc))
            ->getXmlSigned($document);
    }

    /**
     * Envia documento.
     *
     * @param DocumentInterface $document
     *
     * @return BaseResult
     */
    public function send(DocumentInterface $document)
    {
        $classDoc = get_class($document);
        $this->factory
            ->setBuilder($this->getBuilder($classDoc))
            ->setSender($this->getSender($classDoc));

        return $this->factory->send($document);
    }

    /**
     * Envia xml generado.
     *
     * @param string $type Document Type
     * @param string $name Xml Name
     * @param string $xml Xml Content
     * @return BaseResult
     */
    public function sendXml($type, $name, $xml)
    {
        $this->factory
            ->setBuilder($this->getBuilder($type))
            ->setSender($this->getSender($type));

        return $this->factory->sendXml($name, $xml);
    }

    /**
     * @param $ticket
     *
     * @return StatusResult
     */
    public function getStatus($ticket)
    {
        $sender = new ExtService();
        $sender->setClient($this->wsClient);

        return $sender->getStatus($ticket);
    }

    /**
     * @return CpeBuilder
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param string $class
     *
     * @return BuilderInterface
     */
    private function getBuilder($class)
    {
        $builder = $this->builders[$class];

        return new $builder($this->options);
    }

    /**
     * @param string $class
     *
     * @return SenderInterface
     */
    private function getSender($class)
    {
        $sender = in_array($class, $this->summaries) ? new SummarySender() : new BillSender();
        $sender->setClient($this->wsClient);
        if ($this->codeProvider) {
            $sender->setCodeProvider($this->codeProvider);
        }

        return $sender;
    }
}
