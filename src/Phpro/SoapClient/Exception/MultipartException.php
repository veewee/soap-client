<?php

namespace Phpro\SoapClient\Exception;

use Psr\Http\Message\MessageInterface;

/**
 * Class MultipartException
 *
 * @package Phpro\SoapClient\Exception
 */
class MultipartException extends RuntimeException
{
    /**
     * @param string $contentType
     *
     * @return MultipartException
     */
    public static function invalidMessage(MessageInterface $message): MultipartException
    {
        $contentType = $message->getHeaderLine('Content-Type');

        return new self(sprintf('Invalid Content-Type provided. Expected multipart/*, Got: %s', $contentType));
    }

    /**
     * @return MultipartException
     */
    public static function inlineElementNotFound()
    {
        return new self('Could not find the root inline element.');
    }

    /**
     * @return MultipartException
     */
    public static function startElementNotFound()
    {
        return new self('Could not find the root start element.');
    }

    /**
     * @param string $contentId
     *
     * @return MultipartException
     */
    public static function contentElementNotFoundByContentId(string $contentId): MultipartException
    {
        return new self(sprintf('Could not found the multipart element with Content-Id: %s', $contentId));
    }
}
