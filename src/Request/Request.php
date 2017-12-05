<?php

namespace Framework\Base\Request;

/**
 * Class Request
 * @package Framework\Base\Request
 */
abstract class Request implements RequestInterface
{
    /**
     * @var string|null
     */
    private $uri = null;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return RequestInterface
     */
    public function setUri(string $uri): RequestInterface
    {
        $this->uri = $uri;

        return $this;
    }
}
