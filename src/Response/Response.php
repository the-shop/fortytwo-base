<?php

namespace Framework\Base\Response;

/**
 * Class Response
 * @package Framework\Base\Response
 */
abstract class Response implements ResponseInterface
{
    /**
     * @var mixed
     */
    private $body = null;

    /**
     * @var int
     */
    private $code;

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $responseBody
     *
     * @return ResponseInterface
     */
    public function setBody($responseBody): ResponseInterface
    {
        $this->body = $responseBody;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return ResponseInterface
     */
    public function setCode(int $code): ResponseInterface
    {
        $this->code = $code;

        return $this;
    }
}
