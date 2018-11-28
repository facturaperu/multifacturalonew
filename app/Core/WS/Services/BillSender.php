<?php

namespace App\Core\WS\Services;

use App\Core\Helpers\ErrorHelper;
use App\Core\Helpers\Zip\ZipFileDecompress;
use App\Core\Helpers\Zip\ZipFly;
//use App\Core\Helpers\ZipHelper;
use App\Core\WS\Client\WSClient;
use App\Core\WS\Reader\CdrReader;
use Exception;

class BillSender
{
    /**
     * @param WSClient $wsClient
     * @param string $filename
     * @param string $content
     *
     * @return array
     * @throws mixed
     */

    public function send($wsClient, $filename, $content)
    {
        try {
            $result = [];
            $zipContent = (new ZipFly())->compress($filename.'.xml', $content);// (new ZipHelper())->compress($filename.'.xml', $content);
            $params = [
                'fileName' => $filename.'.zip',
                'contentFile' => $zipContent,
            ];

            $response = $wsClient->call('sendBill', ['parameters' => $params]);
            $cdrZip = $response->applicationResponse;
            $cdrXml = (new ZipFileDecompress())->extractResponse($cdrZip);
            $result['success'] = true;
            $result['cdrXml'] = $cdrXml;
            $result['cdrResponse'] = (new CdrReader())->getCdrResponse($cdrXml);
            return $result;

        } catch (\SoapFault $e) {
            $error = ErrorHelper::getErrorFromFault($e);
            throw new Exception($error['message']);
//            $result['success'] = false;
//            $result['message'] = $error['message'];
//            $result['code'] = $error['code'];
        }


    }
}
