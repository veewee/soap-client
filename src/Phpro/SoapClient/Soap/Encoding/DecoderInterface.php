<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Soap\Encoding;

use Phpro\SoapClient\Soap\HttpBinding\SoapResponse;

interface DecoderInterface
{
    public function decode(string $data): SoapResponse;
}
