<?php

namespace Framework\Base;

use Framework\Base\Module\BaseModule;
use Symfony\Component\Dotenv\Dotenv;

class Module extends BaseModule
{
    public function bootstrap()
    {
        $env = new Dotenv();
        $env->load(realpath(dirname(__FILE__, 5) . '/.env'));

        // Let's read all files from module config folder and set to Configuration
        $configDirPath = realpath(dirname(__DIR__)) . '/config/';
        $this->setModuleConfiguration($configDirPath);
    }
}
