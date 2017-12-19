<?php

namespace Framework\Base;

use Framework\Base\Application\ApplicationConfigurationInterface;
use Framework\Base\Application\ApplicationInterface;
use Framework\Base\Module\BaseModule;

/**
 * Class Module
 * @package Framework\Base
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public function loadConfig()
    {
        // Let's read all files from module config folder and set to Configuration
        $configDirPath = realpath(dirname(__DIR__)) . '/config/';
        $this->setModuleConfiguration($configDirPath);
    }

    /**
     * @inheritdoc
     */
    public function bootstrap()
    {
        /**
         * @var ApplicationInterface $application
         */
        $application = $this->getApplication();

        /**
         * @var ApplicationConfigurationInterface $appConfig
         */
        $appConfig = $application->getConfiguration();

        $repositoryManager = $application->getRepositoryManager();

        // Add listeners to application
        if (
            empty($listeners = $appConfig->getPathValue('listeners')) === false
        ) {
            foreach ($listeners as $event => $arrayHandlers) {
                foreach ($arrayHandlers as $handlerClass) {
                    $application->listen($event, $handlerClass);
                }
            }
        }

        //Register Services
        if (
            empty($services = $appConfig->getPathValue('services')) === false
        ) {
            foreach ($services as $serviceName => $config) {
                $application->registerService(new $serviceName($config));
            }
        }

        // Register repositories
        if (
            empty($repositories = $appConfig->getPathValue('repositories')) === false
        ) {
            $repositoryManager->registerRepositories($repositories);
        }

        // Register model adapters
        if (
            empty($modelAdapters = $appConfig->getPathValue('modelAdapters')) === false
        ) {
            foreach ($modelAdapters as $model => $adapters) {
                foreach ($adapters as $adapter) {
                    $repositoryManager->addModelAdapter($model, new $adapter());
                }
            }
        }

        // Register model primary adapters
        if (
            empty($primaryModelAdapter = $appConfig->getPathValue('primaryModelAdapter')) === false
        ) {
            foreach ($primaryModelAdapter as $model => $primaryAdapter) {
                $repositoryManager->setPrimaryAdapter($model, new $primaryAdapter());
            }
        }
    }
}
