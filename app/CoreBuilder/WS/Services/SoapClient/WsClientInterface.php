<?php

namespace App\CoreBuilder\WS\Services\SoapClient;

/**
 * Interface WsClientInterface.
 */
interface WsClientInterface
{
    /**
     * @param $function
     * @param $arguments
     *
     * @return mixed
     */
    public function call($function, $arguments);
}
