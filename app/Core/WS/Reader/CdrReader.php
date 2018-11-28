<?php

namespace App\Core\WS\Reader;

class CdrReader
{
    /**
     * Get Cdr using DomDocument.
     *
     * @param string $xml
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getCdrResponse($xml)
    {
        $xpt = $this->getXpath($xml);
        $cdr = $this->getResponseByXpath($xpt);
        if (!$cdr) {
            throw new \Exception('Not found cdr response in xml');
        }
        return $cdr;
    }

    /**
     * Get Xpath from xml content.
     *
     * @param string $xmlContent
     *
     * @return \DOMXPath
     */
    private function getXpath($xmlContent)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlContent);
        $xpt = new \DOMXPath($doc);
        $xpt->registerNamespace('x', $doc->documentElement->namespaceURI);

        return $xpt;
    }

    /**
     * @param \DOMXPath $xpath
     *
     * @return array
     */
    private function getResponseByXpath(\DOMXPath $xpath)
    {
        $resp = $xpath->query('/x:ApplicationResponse/cac:DocumentResponse/cac:Response');

        if ($resp->length !== 1) {
            return null;
        }
        $obj = $resp[0];

        $cdr['id'] = $this->getValueByName($obj, 'ReferenceID');
        $cdr['code'] = $this->getValueByName($obj, 'ResponseCode');
        $cdr['description'] = $this->getValueByName($obj, 'Description');
        $cdr['notes'] = $this->getNotes($xpath);

        return $cdr;
    }

    /**
     * @param \DOMElement $node
     * @param string      $name
     *
     * @return string
     */
    private function getValueByName(\DOMElement $node, $name)
    {
        $values = $node->getElementsByTagName($name);
        if ($values->length !== 1) {
            return '';
        }

        return $values[0]->nodeValue;
    }

    /**
     * @param \DOMXPath $xpath
     *
     * @return string[]
     */
    private function getNotes(\DOMXPath $xpath)
    {
        $nodes = $xpath->query('/x:ApplicationResponse/cbc:Note');
        $notes = [];
        if ($nodes->length === 0) {
            return $notes;
        }

        /** @var \DOMElement $node */
        foreach ($nodes as $node) {
            $notes[] = $node->nodeValue;
        }

        return $notes;
    }
}
