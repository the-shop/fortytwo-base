<?php

namespace Framework\Base\Service;

use Framework\Base\Application\ApplicationAwareTrait;

/**
 * Class Service
 * @package Framework\Base\Service
 */
abstract class Service implements ServiceInterface
{
    use ApplicationAwareTrait;

    /**
     * @var array
     */
    private $config;

    /**
     * Service constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return ServiceInterface
     */
    public function setConfig(array $config): ServiceInterface
    {
        $this->config = $config;

        return $this;
    }
}
