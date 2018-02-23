<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap;

use Phpro\SoapClient\Soap\Encoding\DecoderInterface;
use Phpro\SoapClient\Soap\Encoding\EncoderInterface;
use Phpro\SoapClient\Soap\Handler\HandlerInterface;
use Phpro\SoapClient\Wsdl\Parser\MethodMapParserInterface;
use Phpro\SoapClient\Wsdl\Parser\TypeMapParserInterface;

interface SoapEngineInterface extends
    EncoderInterface,
    DecoderInterface,
    HandlerInterface,
    MethodMapParserInterface,
    TypeMapParserInterface
{
    public function getWsdl(): string;
}
