<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Wsdl\Parser;

use Phpro\SoapClient\CodeGenerator\Model\ClientMethodMap;

interface MethodMapParserInterface
{
    public function parseMethods(string $typeNamespace): ClientMethodMap;
}
