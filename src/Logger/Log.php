<?php

namespace Framework\Base\Logger;

/**
 * Class Log
 * @package Framework\Base\Logger
 */
class Log implements LogInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string|\Exception
     */
    private $payload;

    /**
     * @var bool
     */
    private $isException = false;

    /**
     * Log constructor.
     *
     * @param $payload
     */
    public function __construct($payload)
    {
        if ($payload instanceof \Exception) {
            $this->isException = true;
        }
        $this->payload = $payload;
    }

    /**
     * @return \Exception|string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getData(string $key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return LogInterface
     */
    public function setData(string $key, $value): LogInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isException(): bool
    {
        return $this->isException;
    }
}
