<?php

namespace App\CoreBuilder\WS\Services\SoapClient;

use App\CoreBuilder\WS\Services\SoapClient\Header\WSSESecurityHeader;

class WsSoapClient implements WsClientInterface
{
    private $client;

    /**
     * SoapClient constructor.
     *
     * @param string $wsdl       Url of WSDL
     * @param array  $parameters Soap's parameters
     */
    public function __construct($wsdl = '', $parameters = [])
    {
        if (empty($wsdl)) {
            $wsdl = __DIR__.'/../Resources/wsdl/billService.wsdl';
        }
        $this->client = new \SoapClient($wsdl, $parameters);
    }

    /**
     * @param $user
     * @param $password
     */
    public function setCredentials($user, $password)
    {
        $this->client->__setSoapHeaders(new WSSESecurityHeader($user, $password));
    }

    /**
     * Set Url of Service.
     *
     * @param string $url
     */
    public function setService($url)
    {
        $this->client->__setLocation($url);
    }

    /**
     * @param $function
     * @param $arguments
     *
     * @return mixed
     */
    public function call($function, $arguments)
    {
        return $this->client->__soapCall($function, $arguments);
    }
}
