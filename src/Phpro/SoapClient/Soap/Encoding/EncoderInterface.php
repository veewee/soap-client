<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Encoding;

use Phpro\SoapClient\Soap\HttpBinding\SoapRequest;

interface EncoderInterface
{
    public function encode($data): SoapRequest;
}
