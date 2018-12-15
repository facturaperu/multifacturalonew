<?php

namespace App\CoreBuilder;

use App\CoreBuilder\WS\Services\SunatEndpoints;
use App\CoreBuilder\XmlDsig\Sunat\SignedXml;

class Util
{
    public function getCpeBuilder($endpoint = SunatEndpoints::FE_BETA)
    {
        $path_certificate = app_path('CoreBuilder'.DIRECTORY_SEPARATOR.'Certificate'.DIRECTORY_SEPARATOR.'cert.pem');
        $path_cache = storage_path('framework'.DIRECTORY_SEPARATOR.'cache');



//        $signer = new SignedXml();
//        $signer->setCertificateFromFile($path_certificate);


        $cpeBuilder = new CpeBuilder();
        $cpeBuilder->setService($endpoint);
        $cpeBuilder->setCertificate(file_get_contents($path_certificate));
        $cpeBuilder->setCredentials('20000000000MODDATOS', 'moddatos');
        $cpeBuilder->setCachePath($path_cache);

        return $cpeBuilder;
    }
}