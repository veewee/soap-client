<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Engine\ExtSoap;

use Phpro\SoapClient\CodeGenerator\Model\ClientMethodMap;
use Phpro\SoapClient\CodeGenerator\Model\TypeMap;
use Phpro\SoapClient\Soap\Encoding\DecoderInterface;
use Phpro\SoapClient\Soap\Encoding\EncoderInterface;
use Phpro\SoapClient\Soap\Handler\HandlerInterface;
use Phpro\SoapClient\Soap\HttpBinding\SoapRequest;
use Phpro\SoapClient\Soap\HttpBinding\SoapResponse;
use Phpro\SoapClient\Soap\SoapEngineInterface;

class ExtSoapEngine implements SoapEngineInterface
{
    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var HandlerInterface
     */
    private $handler;

    public function __construct(
        SoapClient $soapClient,
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        HandlerInterface $handler
    )
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->handler = $handler;
    }

    public function decode(string $data): SoapResponse
    {
        return $this->decoder->decode($data);
    }

    public function encode($data): SoapRequest
    {
        return $this->encoder->encode($data);
    }

    public function request(SoapRequest $request): SoapResponse
    {
        return $this->handler->request($request);
    }

    public function getWsdl(): string
    {
        // TODO: Implement getWsdl() method.
    }

    public function getTypes(): TypeMap
    {
        // TODO: Implement getTypes() method.
    }

    public function getMethods(): ClientMethodMap
    {
        // TODO: Implement getMethods() method.
    }


}
