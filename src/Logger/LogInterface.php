<?php

namespace Framework\Base\Logger;

/**
 * Interface LogInterface
 * @package Framework\Base\Logger
 */
interface LogInterface
{
    /**
     * @param string $key
     * @param        $value
     *
     * @return mixed
     */
    public function setData(string $key, $value): LogInterface;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getData(string $key);

    /**
     * @return array
     */
    public function getAllData(): array;

    /**
     * @return mixed
     */
    public function getPayload();

    /**
     * @return bool
     */
    public function isException(): bool;
}
