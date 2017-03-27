<?php

namespace Phpro\SoapClient\Type;

/**
 * Class XopInclude
 *
 * @package Phpro\SoapClient\Type
 */
class XopInclude
{
    /**
     * @var string
     */
    private $href;

    /**
     * XopInclude constructor.
     *
     * @param $href
     */
    public function __construct($href)
    {
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return bool
     */
    public function isCid()
    {
        return preg_match('/^cid\:/i', $this->href);
    }

    /**
     * @return bool
     */
    public function isMid()
    {
        return preg_match('/^mid\:/i', $this->href);
    }

    /**
     * @return bool
     */
    public function getId()
    {
        return preg_replace('/^(cid|mid)\:(.*)/i', '$2', $this->href);
    }
}
