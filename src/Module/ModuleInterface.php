<?php

namespace Framework\Base\Module;

use Framework\Base\Application\ApplicationAwareInterface;

/**
 * Interface ModuleInterface
 * @package Framework\Base\Module
 */
interface ModuleInterface extends ApplicationAwareInterface
{
    /**
     * Load module configuration into application configuration instance
     *
     * @return void
     */
    public function loadConfig();

    /**
     * Bootstrap module
     *
     * @return void
     */
    public function bootstrap();

    /**
     * @param string $configDirPath
     *
     * @return ModuleInterface
     */
    public function setModuleConfiguration(string $configDirPath): ModuleInterface;
}
