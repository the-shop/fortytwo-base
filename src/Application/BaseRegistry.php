<?php

namespace Framework\Base\Application;

/**
 * Class BaseRegistry
 * @package Framework\Base\Application
 */
class BaseRegistry implements RegistryInterface
{
    use ApplicationAwareTrait;

    /**
     * @var array
     */
    private $content = [];

    /**
     * @param string $key
     * @param        $value
     * @param bool   $overwrite
     *
     * @return RegistryInterface
     * @throws \RuntimeException
     */
    public function register(string $key, $value, bool $overwrite = false): RegistryInterface
    {
        if (isset($this->content[$key]) === true && $overwrite === false) {
            throw new \RuntimeException('Key "' . $key . '" is already registered.');
        }

        $this->content[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function get(string $key)
    {
        if (isset($this->content[$key]) === false) {
            throw new \RuntimeException('Key "' . $key . '" is not registered.');
        }

        return $this->content[$key];
    }

    /**
     * @param string $key
     *
     * @return RegistryInterface
     */
    public function delete(string $key): RegistryInterface
    {
        if (isset($this->content[$key]) === true) {
            unset($this->content[$key]);
        }

        return $this;
    }
}
