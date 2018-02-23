<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Engine\ExtSoap\WsdlParser;

use Phpro\SoapClient\CodeGenerator\Model\ClientMethod;
use Phpro\SoapClient\CodeGenerator\Model\ClientMethodMap;
use Phpro\SoapClient\CodeGenerator\Model\Parameter;
use Phpro\SoapClient\Soap\Engine\ExtSoap\SoapClient;
use Phpro\SoapClient\Wsdl\Parser\MethodMapParserInterface;

class MethodMapParser implements MethodMapParserInterface
{
    /**
     * @var SoapClient
     */
    private $client;

    public function __construct(SoapClient $client, string $namespace)
    {
        $this->client = $client;
    }

    public function parseMethods(string $typeNamespace): ClientMethodMap
    {
        $methods = [];
        foreach ($this->client->__getFunctions() as $functionString) {
            $methods[] = new ClientMethod(
                $this->parseName($functionString),
                $this->parseParameters($typeNamespace, $functionString),
                $this->parseReturnType($functionString),
                $typeNamespace
            );
        }

        return new ClientMethodMap($methods);
    }

    private function parseParameters(string $typeNamespace, string $functionString) : array
    {
        preg_match('/\((.*)\)/', $functionString, $properties);
        $parameters = [];
        $properties = explode(',', $properties[1]);
        foreach ($properties as $property) {
            list($type) = explode(' ', $property);
            $parameters[] = new Parameter($type, $typeNamespace.'\\'.$type);
        }

        return $parameters;
    }

    private function parseName(string $functionString) : string
    {
        preg_match('/^\w+ (\w+)/', $functionString, $matches);

        return $matches[1];
    }

    private function parseReturnType(string $functionString): string
    {
        preg_match('/^(\w+)/', $functionString, $matches);

        return $matches[1];
    }
}
