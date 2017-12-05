<?php

namespace Framework\Base\Service;

use Framework\Base\Application\ApplicationAwareInterface;

/**
 * Interface ServiceInterface
 * @package Framework\Base\Service
 */
interface ServiceInterface extends ApplicationAwareInterface
{
    /**
     * ServiceInterface constructor.
     *
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return array
     */
    public function getConfig(): array;

    /**
     * @param array $config
     *
     * @return ServiceInterface
     */
    public function setConfig(array $config): ServiceInterface;
}
