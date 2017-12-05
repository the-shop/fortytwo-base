<?php

namespace Framework\Base\Application;

/**
 * Interface ApplicationConfigurationInterface
 * @package Framework\Base\Application
 */
interface ApplicationConfigurationInterface extends ConfigurationInterface
{
    /**
     * @return array
     */
    public function getRegisteredModules(): array;

    /**
     * @param array $modules
     *
     * @return ApplicationConfigurationInterface
     */
    public function setRegisteredModules(array $modules): ApplicationConfigurationInterface;
}
