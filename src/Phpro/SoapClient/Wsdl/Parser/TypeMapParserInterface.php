<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Wsdl\Parser;

use Phpro\SoapClient\CodeGenerator\Model\TypeMap;

interface TypeMapParserInterface
{
    public function parseTypes(string $typeNamespace): TypeMap;
}
