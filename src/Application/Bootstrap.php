<?php

namespace Framework\Base\Application;

/**
 * Class Bootstrap
 * @package Framework\Base\Application
 */
class Bootstrap
{
    /**
     * @var array
     */
    private $registerModules = [];

    /**
     * @param array           $moduleInterfaceClassNames
     * @param BaseApplication $application
     *
     * @return Bootstrap
     */
    public function registerModules(array $moduleInterfaceClassNames, BaseApplication $application): Bootstrap
    {
        $this->registerModules = $moduleInterfaceClassNames;

        foreach ($this->registerModules as $moduleClass) {
            /* @var \Framework\Base\Module\ModuleInterface $instance */
            $instance = new $moduleClass();
            $instance->setApplication($application);
            $instance->bootstrap();
        }

        return $this;
    }
}
