<?php

namespace Phpro\SoapClient\Http;

use GuzzleHttp\Psr7\MessageTrait;
use Phpro\SoapClient\Exception\MultipartException;
use Psr\Http\Message\MessageInterface;

use function GuzzleHttp\Psr7\stream_for;

/**
 * Class MultipartMessage
 *
 * @package Phpro\SoapClient\Http
 */
class MultipartMessage implements MessageInterface
{

    use MessageTrait;

    /**
     * @var MultipartElement[]
     */
    private $elements = [];

    /**
     * @var string
     */
    private $boundary;

    /**
     * MultipartMessage constructor.
     *
     * @param array              $headers
     * @param MultipartElement[] $elements
     * @param string             $boundary
     */
    public function __construct(array $headers, array $elements = [], string $boundary = '')
    {
        $this->setHeaders($headers);
        $this->addElements($elements);
        $this->boundary = $boundary ?: uniqid('soapclient', true);
    }

    /**
     * @param MessageInterface $message
     *
     * @return MultipartMessage
     */
    public static function fromMessage(MessageInterface $message): MultipartMessage
    {
        if (!self::isMultipartMessage($message)) {
            throw MultipartException::invalidMessage($message);
        }

        $contentType = $message->getHeaderLine('Content-Type');
        $boundary = preg_match('{boundary="([^"].*)"}i', $contentType);

        $message->getBody()->rewind();
        $body = $message->getBody()->getContents();

        $rawElements = preg_split(sprintf('#--%s#i', preg_quote('boundary', '#')), $body);
        $elements = array_map(function($element) {
            return MultipartElement::fromMessage($element);
        }, array_filter($rawElements));

        return new self($message->getHeaders(), $elements, $boundary);
    }

    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    public static function isMultipartMessage(MessageInterface $message)
    {
        return (bool) preg_match('{multipart/(.*)}i', $message->getHeaderLine('Content-Type'));
    }

    /**
     * @return string
     */
    public function getBoundary(): string
    {
        return $this->boundary;
    }

    /**
     * @return MultipartElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param string $contentId
     *
     * @return MultiPartElement
     * @throws MultipartException
     */
    public function getElementByContentId(string $contentId) : MultiPartElement
    {
        foreach ($this->elements as $element) {
            if ($contentId === $element->getHeaderLine('Content-Id')) {
                return $element;
            }
        }

        throw MultipartException::contentElementNotFoundByContentId($contentId);
    }

    /**
     * @param string $contentType
     *
     * @return MultipartElement[]
     */
    public function filterByContentType(string $contentType) : array
    {
        return array_filter($this->elements, function(MultipartElement $element) use ($contentType) {
            return (bool) preg_match(
                sprintf('#^%s;?(*)#i', preg_quote($contentType, '#')),
                $element->getHeaderLine('Content-Type')
            );
        });
    }

    /**
     * @param string $contentDisposition
     *
     * @return MultipartElement[]
     */
    public function filterByContentDisposition(string $contentDisposition)
    {
        return array_filter($this->elements, function(MultipartElement $element) use ($contentDisposition) {
            $header = $element->getHeaderLine('Content-Disposition');
            $parts = explode(';', $header, 2);

            return $parts[0] === $contentDisposition;
        });
    }

    /**
     * @param MultipartElement[] $elements
     */
    public function addElements(array $elements)
    {
        foreach ($elements as $element) {
            $this->addElements($element);
        }
    }

    /**
     * @param MultipartElement $element
     */
    public function addElement(MultipartElement $element)
    {
        $this->elements[] = $element;
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody()
    {
        $boundary = '--' . $this->boundary . "\n";
        $content = $boundary;
        foreach ($this->elements as $element) {
            $element->getBody()->rewind();
            $content .= trim($element->getBody()->getContents()) . "\n";
            $content .= $boundary;
        }

        return stream_for($content);
    }

    /**
     * Get the multipart/related start element if possible.
     *
     * @link https://tools.ietf.org/html/rfc2387
     *
     * @return MultipartElement
     * @throws \Phpro\SoapClient\Exception\MultipartException
     */
    public function getStartElement()
    {
        $parts = explode(';', $this->getHeaderLine('Content-Type'), 2);
        $parameters = $parts[1] ?: '';
        $contentId = preg_match('{start="{[^"]*}"}i', $parameters);

        if (!$contentId) {
            return $this->elements[0];
        }

        return $this->getElementByContentId($contentId);
    }

    /**
     * Get the Content-Disposition inline element if possible.
     *
     * @link https://tools.ietf.org/html/rfc6266
     *
     * @return MultipartElement
     * @throws \Phpro\SoapClient\Exception\MultipartException
     */
    public function getInlineElement()
    {
        $elements = $this->filterByContentDisposition('inline');
        if (count($elements) !== 1) {
            throw MultipartException::inlineElementNotFound();
        }

        return $elements[0];
    }
}
