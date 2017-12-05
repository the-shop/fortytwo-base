<?php

namespace Framework\Base\Application;

/**
 * Interface RegistryInterface
 * @package Framework\Base\Application
 */
interface RegistryInterface extends ApplicationAwareInterface
{
    /**
     * @param string $key
     * @param        $value
     * @param bool   $overwrite
     *
     * @return RegistryInterface
     */
    public function register(string $key, $value, bool $overwrite = false): RegistryInterface;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     *
     * @return RegistryInterface
     */
    public function delete(string $key): RegistryInterface;
}
