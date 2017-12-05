<?php

namespace Framework\Base\Request;

/**
 * Interface RequestInterface
 * @package Framework\Base\Request
 */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @param string $uri
     *
     * @return RequestInterface
     */
    public function setUri(string $uri): RequestInterface;
}
