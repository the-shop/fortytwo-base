<?php

namespace Framework\Base\Application;

/**
 * Class ApplicationConfiguration
 * @package Framework\Base\Application
 */
class ApplicationConfiguration extends Configuration
{
    /**
     * @var array
     */
    private $registeredModules = [];

    public function __construct(array $configurationValues = [])
    {
        parent::__construct($configurationValues);

        $this->setRootPath();
    }

    /**
     * @param array $modules
     */
    public function setRegisteredModules(array $modules)
    {
        $this->registeredModules = $modules;
    }

    /**
     * @return array
     */
    public function getRegisteredModules()
    {
        return $this->registeredModules;
    }

    public function setRootPath(): ConfigurationInterface
    {
        $this->setPathValue('rootPath', realpath(dirname(__FILE__, 6)));

        return $this;
    }

    public function getRootPath()
    {
        return $this->getPathValue('rootPath');
    }
}
