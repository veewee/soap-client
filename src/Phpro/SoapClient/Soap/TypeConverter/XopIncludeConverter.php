<?php

namespace Phpro\SoapClient\Soap\TypeConverter;

use DOMDocument;
use Phpro\SoapClient\Type\XopInclude;

/**
 * Class XopIncludeConverter
 *
 * @package Phpro\SoapClient\Soap\TypeConverter
 */
class XopIncludeConverter implements TypeConverterInterface
{
    /**
     * @return string
     */
    public function getTypeNamespace()
    {
        return 'http://www.w3.org/2004/08/xop/include"';
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return 'Include';
    }

    /**
     * @param string $data
     *
     * @return XopInclude
     */
    public function convertXmlToPhp($data)
    {
        $doc = new DOMDocument();
        $doc->loadXML($data);

        return new XopInclude($doc->attributes->getNamedItem('href')->nodeValue);
    }

    /**
     * @param XopInclude $php
     *
     * @return string
     */
    public function convertPhpToXml($php)
    {
        return sprintf('<%1$s>%2$s</%1$s>', $this->getTypeName(), $php->getHref());
    }
}
