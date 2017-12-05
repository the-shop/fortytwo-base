<?php

namespace Framework\Base\Service;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Application\BaseRegistry;
use Framework\Base\Application\RegistryInterface;

/**
 * Class ServicesRegistry
 * @package Framework\Base\Service
 */
class ServicesRegistry extends BaseRegistry
{
    use ApplicationAwareTrait;

    /**
     * @param ServiceInterface $service
     * @param bool             $overwrite
     *
     * @return $this
     */
    public function registerService(ServiceInterface $service, bool $overwrite = false)
    {
        $service->setApplication($this->getApplication());
        $this->register($service->getIdentifier(), $service, $overwrite);

        return $this;
    }

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
        if ($value instanceof ServiceInterface) {
            return parent::register($key, $value, $overwrite);
        }

        throw new \RuntimeException('Can not register service that does not implement "ServiceInterface"');
    }
}
