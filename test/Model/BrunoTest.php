<?php

namespace Framework\Base\Test;

use Framework\Base\Model\Modifiers\LowerCaseFilter;
use Framework\Base\Model\Modifiers\TrimFilter;

/**
 * Class BrunoTest
 * @package Framework\Base\Test
 */
class BrunoTest extends UnitTest
{
    /**
     * Test Bruno model field modifiers
     */
    public function testBrunoAddAttributesWithFieldModifiers()
    {
        $appConfig = $this->getApplication()
                          ->getConfiguration();

        $repositoryManager = $this->getApplication()
                                  ->getRepositoryManager();

        $appConfig->readFromJson(dirname(__DIR__) . '/Dummies/dummyConfigJson.json');
        $appConfig->readFromPhp(dirname(__DIR__) . '/Dummies/dummyConfig.php');

        // Format models configuration
        $modelsConfiguration = $this->generateModelsConfiguration(
            $appConfig->getPathValue('models')
        );

        $modelAdapters = $appConfig->getPathValue('modelAdapters');
        // Register model adapters
        foreach ($modelAdapters as $model => $adapters) {
            foreach ($adapters as $adapter) {
                $repositoryManager->addModelAdapter($model, new $adapter());
            }
        }

        $primaryModelAdapter = $appConfig->getPathValue('primaryModelAdapter');
        // Register model primary adapters
        foreach ($primaryModelAdapter as $model => $primaryAdapter) {
            $repositoryManager->setPrimaryAdapter($model, new $primaryAdapter());
        }

        // Register resources, repositories and model fields
        $repositoryManager->registerResources($modelsConfiguration['resources'])
                          ->registerRepositories($appConfig->getPathValue('repositories'))
                          ->registerModelFields($modelsConfiguration['modelFields']);

        $model = $repositoryManager->getRepositoryFromResourceName('tests')
                                   ->newModel()
                                   ->addFieldFilter('name', new LowerCaseFilter())
                                   ->addFieldFilter('email', new TrimFilter())
                                   ->setAttributes([
                                       'name' => 'TESTING',
                                       'email' => ' test@test.com ',
                                       'password' => 'test password',
                                                   ]
                                   );

        $attributes = $model->getAttributes();

        $this->assertEquals('testing', $attributes['name']);
        $this->assertEquals('test@test.com', $attributes['email']);
        $this->assertNotEquals('test password', $attributes['password']);
        $this->assertEquals(
            true,
            password_verify('test password', $attributes['password'])
        );
    }
}
