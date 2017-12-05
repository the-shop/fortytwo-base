<?php

namespace Framework\Base\Application;

/**
 * Class ApplicationConfiguration
 * @package Framework\Base\Application
 */
class ApplicationConfiguration extends Configuration implements ApplicationConfigurationInterface
{
    /**
     * @var array
     */
    private $registeredModules = [];

    /**
     * @return array
     */
    public function getRegisteredModules(): array
    {
        return $this->registeredModules;
    }

    /**
     * @param array $modules
     *
     * @return ApplicationConfigurationInterface
     */
    public function setRegisteredModules(array $modules): ApplicationConfigurationInterface
    {
        $this->registeredModules = $modules;

        return $this;
    }
}
