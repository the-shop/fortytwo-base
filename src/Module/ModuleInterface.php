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
     * Bootstrap module
     */
    public function bootstrap();

    /**
     * @param string $configDirPath
     *
     * @return ModuleInterface
     */
    public function setModuleConfiguration(string $configDirPath): ModuleInterface;
}
