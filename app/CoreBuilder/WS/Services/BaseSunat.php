<?php

namespace App\CoreBuilder\WS\Services;

use App\CoreBuilder\Interfaces\ErrorCodeProviderInterface;
use App\CoreBuilder\WS\Reader\CdrReaderInterface;
use App\CoreBuilder\WS\Reader\DomCdrReader;
use App\CoreBuilder\WS\Response\BillResult;
use App\CoreBuilder\WS\Response\CdrResponse;
use App\CoreBuilder\WS\Response\Error;
use App\CoreBuilder\WS\Services\SoapClient\WsClientInterface;
use App\CoreBuilder\Zip\CompressInterface;
use App\CoreBuilder\Zip\DecompressInterface;
use App\CoreBuilder\Zip\ZipFileDecompress;
use App\CoreBuilder\Zip\ZipFly;

/**
 * Class BaseSunat.
 */
class BaseSunat
{
    const NUMBER_PATTERN = '/[^0-9]+/';

    /**
     * @var CompressInterface
     */
    private $compressor;

    /**
     * @var DecompressInterface
     */
    private $decompressor;

    /**
     * @var CdrReaderInterface
     */
    private $cdrReader;

    /**
     * @var WsClientInterface
     */
    private $client;

    /**
     * @var ErrorCodeProviderInterface
     */
    private $codeProvider;

    /**
     * @param ErrorCodeProviderInterface $codeProvider
     */
    public function setCodeProvider(ErrorCodeProviderInterface $codeProvider)
    {
        $this->codeProvider = $codeProvider;
    }

    /**
     * BaseSunat constructor.
     */
    public function __construct()
    {
        //TODO: Inject
        $this->compressor = new ZipFly();
        $this->decompressor = new ZipFileDecompress();
        $this->cdrReader = new DomCdrReader();
    }

    /**
     * @return WsClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param WsClientInterface $client
     *
     * @return BaseSunat
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get error from Fault Exception.
     *
     * @param \SoapFault $fault
     *
     * @return Error
     */
    protected function getErrorFromFault(\SoapFault $fault)
    {
        $error = $this->getErrorByCode($fault->faultcode, $fault->faultstring);

        if (empty($error->getMessage())) {
            $error->setMessage(isset($fault->detail) ? $fault->detail->message : $fault->faultstring);
        }

        return $error;
    }

    /**
     * @param string $code
     * @param string $optional Intenta obtener el codigo de este parametro sino $codigo no es válido.
     *
     * @return Error
     */
    protected function getErrorByCode($code, $optional = '')
    {
        $error = new Error();
        $error->setCode($code);
        $code = preg_replace(self::NUMBER_PATTERN, '', $code);
        $message = '';

        if (empty($code) && $optional) {
            $code = preg_replace(self::NUMBER_PATTERN, '', $optional);
        }

        if ($code) {
            $message = $this->getMessageError($code);
            $error->setCode($code);
        }

        return $error->setMessage($message);
    }

    /**
     * @param string $filename
     * @param string $xml
     *
     * @return string
     */
    protected function compress($filename, $xml)
    {
        return $this->compressor->compress($filename, $xml);
    }

    /**
     * @param $zipContent
     *
     * @return CdrResponse
     */
    protected function extractResponse($zipContent)
    {
        $xml = $this->getXmlResponse($zipContent);

        return $this->cdrReader->getCdrResponse($xml);
    }

    /**
     * @param $code
     *
     * @return string
     */
    protected function getMessageError($code)
    {
        if (empty($this->codeProvider)) {
            return '';
        }

        return $this->codeProvider->getValue($code);
    }

    protected function isExceptionCode($code)
    {
        $value = intval($code);

        return $value >= 100 && $value <= 1999;
    }

    protected function loadErrorByCode(BillResult $result, $code)
    {
        $error = $this->getErrorByCode($code);

        if (empty($error->getMessage()) && $result->getCdrResponse()) {
            $error->setMessage($result->getCdrResponse()->getDescription());
        }

        $result
            ->setSuccess(false)
            ->setError($error);
    }

    private function getXmlResponse($content)
    {
        $filter = function ($filename) {
            return 'xml' === strtolower($this->getFileExtension($filename));
        };
        $files = $this->decompressor->decompress($content, $filter);

        return 0 === count($files) ? '' : $files[0]['content'];
    }

    private function getFileExtension($filename)
    {
        $lastDotPos = strrpos($filename, '.');
        if (!$lastDotPos) {
            return '';
        }

        return substr($filename, $lastDotPos + 1);
    }
}
