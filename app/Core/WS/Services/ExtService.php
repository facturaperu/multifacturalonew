<?php

namespace App\Core\WS\Services;

use App\Core\Helpers\ErrorHelper;
use App\Core\Helpers\Zip\ZipFileDecompress;
//use App\Core\Helpers\ZipHelper;
use App\Core\WS\Client\WSClient;
use App\Core\WS\Reader\CdrReader;
use Exception;
use SoapFault;

class ExtService
{
    /**
     * @param WSClient $wsClient
     * @param string $ticket
     *
     * @return array
     * @throws mixed
     */
    public function getStatus($wsClient, $ticket)
    {
        $result = [];

        try {
            $params = [
                'ticket' => $ticket,
            ];
            $response = $wsClient->call('getStatus', ['parameters' => $params]);
            $status = $response->status;
            $code = $status->statusCode;

            $result['success'] = true;
            $result['code'] = $code;

            if ($code == '0' || $code == '99') {
                if ($status->content) {
                    $cdrXml = (new ZipFileDecompress())->extractResponse($status->content);
//                    $cdrXml = (new ZipHelper())->decompressXmlFile($status->content);
                    $result['cdrXml'] = $cdrXml;
                    $result['cdrResponse'] = (new CdrReader())->getCdrResponse($cdrXml);
//                    dd($result['cdrResponse']);
                }
            } else {
                $message = ErrorHelper::getMessageError($code);
//                dd($message);
                throw new Exception($message);
//                $result['success'] = false;
//                $result['message'] = ErrorHelper::getMessageError($code);
            }
        } catch (SoapFault $e) {
            $error = ErrorHelper::getErrorFromFault($e);
            throw new Exception($error['message']);
//            $result['success'] = false;
//            $result['message'] = $error['message'];
//            $result['code'] = $error['code'];
        }

        return $result;
    }

    /**
     * @param WSClient $wsClient
     * @param string $companyNumber
     * @param string $type
     * @param string $series
     * @param string $number
     *
     * @return array
     * @throws mixed
     */
    public function getCdrStatus($wsClient, $companyNumber, $type, $series, $number)
    {
        $result = [];

        try {
            $params = [
                'rucComprobante' => $companyNumber,
                'tipoComprobante' => $type,
                'serieComprobante' => $series,
                'numeroComprobante' => $number,
            ];

            $response = $wsClient->call('getStatusCdr', ['parameters' => $params]);
            $statusCdr = $response->statusCdr;

            $result['success'] = true;
            $result['code'] = $statusCdr->statusCode;
            $result['message'] = $statusCdr->statusMessage;

            if ($statusCdr->content) {
                $cdrXml = (new ZipFileDecompress())->extractResponse($statusCdr->content);
                //$cdrXml = (new ZipHelper())->decompressXmlFile($statusCdr->content);
                $result['cdrXml'] = $cdrXml;
                $result['cdrResponse'] = (new CdrReader())->getCdrResponse($cdrXml);
            }
        } catch (SoapFault $e) {
            $result['success'] = false;
            $result['error'] =  ErrorHelper::getErrorFromFault($e);
        }

        return $result;
    }
}
