<?php

namespace Framework\Base\Test;

use Framework\Base\Application\ApplicationConfiguration;
use Framework\Base\Application\ApplicationInterface;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Test\Dummies\DummyApplication;
use Framework\Base\Test\Dummies\MemoryRenderer;
use Framework\Base\Test\Dummies\TestRepository;
use PHPUnit\Framework\TestCase;

/**
 * All tests should extend this class
 * Class UnitTest
 * @package Framework\Base\Test
 */
class UnitTest extends TestCase
{
    /**
     * @var ApplicationInterface|null
     */
    private $application = null;

    /**
     * UnitTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $appConfig = new ApplicationConfiguration();

        $this->setApplication(new DummyApplication($appConfig));
    }

    /**
     *
     */
    public function testIgnore()
    {
        $this->assertEquals(true, true);
    }

    /**
     * @param \Framework\Base\Request\RequestInterface $request
     *
     * @return ApplicationInterface|null
     */
    protected function runApplication(RequestInterface $request)
    {
        $app = $this->application;

        $app->setRequest($request);

        $app->setRenderer(new MemoryRenderer());

        try {
            $app->run();
        } catch (\Exception $e) {
            $app->getExceptionHandler()
                ->handle($e);
        }

        return $app;
    }

    /**
     * @return ApplicationInterface|null
     */
    protected function getApplication()
    {
        if ($this->application === null) {
            $this->application->buildRequest();
        }

        return $this->application;
    }

    /**
     * @param \Framework\Base\Application\ApplicationInterface $app
     *
     * @return $this
     */
    protected function setApplication(ApplicationInterface $app)
    {
        $this->application = $app;

        return $this;
    }

    /**
     * @param $modelsConfig
     *
     * @return array
     */
    protected function generateModelsConfiguration(array $modelsConfig)
    {
        $generatedConfiguration = [
            'resources' => [],
            'modelFields' => [],
        ];
        foreach ($modelsConfig as $modelName => $options) {
            $generatedConfiguration['resources'][$options['collection']] = TestRepository::class;
            $generatedConfiguration['modelFields'][$options['collection']] = $options['fields'];
        }

        return $generatedConfiguration;
    }
}
