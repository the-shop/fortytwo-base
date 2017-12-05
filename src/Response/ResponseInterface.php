<?php

namespace Framework\Base\Response;

/**
 * Interface ResponseInterface
 * @package Framework\Base\Response
 */
interface ResponseInterface
{
    /**
     * @param $responseBody
     *
     * @return ResponseInterface
     */
    public function setBody($responseBody): ResponseInterface;

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param int $code
     *
     * @return ResponseInterface
     */
    public function setCode(int $code): ResponseInterface;

    /**
     * @return int
     */
    public function getCode(): int;
}
