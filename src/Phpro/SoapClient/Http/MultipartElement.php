<?php

namespace Phpro\SoapClient\Http;

use GuzzleHttp\Psr7\MessageTrait;
use Psr\Http\Message\MessageInterface;

use function GuzzleHttp\Psr7\_parse_message;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class MultipartElement
 *
 * @package Phpro\SoapClient\Http
 */
class MultipartElement implements MessageInterface
{
    use MessageTrait;

    /**
     * MultipartElement constructor.
     *
     * @param array  $headers
     * @param string $body
     */
    public function __construct(array $headers, string $body)
    {
        $this->setHeaders($headers);
        $this->stream = stream_for($body);
    }

    /**
     * @param string $message
     *
     * @return MultipartElement
     */
    public static function fromMessage(string $message): MultipartElement
    {
        $parts = _parse_message($message);

        return new self($parts['headers'], $parts['body']);
    }
}
