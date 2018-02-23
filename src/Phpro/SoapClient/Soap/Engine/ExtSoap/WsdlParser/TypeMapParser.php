<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Engine\ExtSoap\WsdlParser;

use Phpro\SoapClient\CodeGenerator\Model\Type;
use Phpro\SoapClient\CodeGenerator\Model\TypeMap;
use Phpro\SoapClient\Soap\Engine\ExtSoap\SoapClient;
use Phpro\SoapClient\Wsdl\Parser\TypeMapParserInterface;

class TypeMapParser implements TypeMapParserInterface
{
    /**
     * @var \SoapClient
     */
    private $client;

    /**
     * @var array
     */
    private $types = [];

    public function __construct(SoapClient $client)
    {
        $this->client = $client;
    }

    public function parseTypes(string $typeNamespace): TypeMap
    {
        $types = [];
        foreach ($this->getSoapTypes() as $type => $properties) {
            $types[] = new Type($typeNamespace, $type, $properties);
        }

        return new TypeMap($typeNamespace, $types);
    }

    /**
     * Retrieve SOAP types from the WSDL and parse them
     *
     * @return array Array of types and their properties
     */
    private function getSoapTypes(): array
    {
        if ($this->types) {
            return $this->types;
        }

        $soapTypes = $this->client->__getTypes();
        foreach ($soapTypes as $soapType) {
            $properties = [];
            $lines = explode("\n", $soapType);
            if (!preg_match('/struct (.*) {/', $lines[0], $matches)) {
                continue;
            }
            $typeName = $matches[1];

            foreach (array_slice($lines, 1) as $line) {
                if ($line === '}') {
                    continue;
                }
                preg_match('/\s* (.*) (.*);/', $line, $matches);
                $properties[$matches[2]] = $matches[1];
            }

            $this->types[$typeName] = $properties;
        }

        return $this->types;
    }
}
