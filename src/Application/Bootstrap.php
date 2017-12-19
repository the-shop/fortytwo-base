<?php

namespace Framework\Base\Application;

use Framework\Base\Module\ModuleInterface;

/**
 * Class Bootstrap
 * @package Framework\Base\Application
 */
class Bootstrap
{
    /**
     * @var ModuleInterface[]
     */
    private $modules = [];

    /**
     * @param array           $moduleInterfaceClassNames
     * @param BaseApplication $application
     *
     * @return Bootstrap
     */
    public function loadModulesConfigs(array $moduleInterfaceClassNames, BaseApplication $application): Bootstrap
    {
        foreach ($moduleInterfaceClassNames as $moduleClass) {
            /* @var \Framework\Base\Module\ModuleInterface $module */
            $module = new $moduleClass();
            $module->setApplication($application)
                   ->loadConfig();

            $this->modules[] = $module;
        }

        return $this;
    }

    /**
     * @return Bootstrap
     */
    public function registerModules(): Bootstrap
    {
        foreach ($this->modules as $module) {
            $module->bootstrap();
        }

        return $this;
    }
}
