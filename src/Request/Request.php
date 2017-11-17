<?php

namespace Framework\Base\Request;

abstract class Request implements RequestInterface
{
    /**
     * @var string|null
     */
    private $uri = null;

    public function setUri(string $uri): RequestInterface
    {
        $this->uri = $uri;

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
