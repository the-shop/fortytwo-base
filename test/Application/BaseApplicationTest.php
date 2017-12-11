<?php

namespace Framework\Base\Test\Application;

use Framework\Base\Application\ApplicationConfiguration;
use Framework\Base\Application\BaseApplication;
use Framework\Base\Application\ControllerInterface;
use Framework\Base\Application\Exception\ExceptionHandler;
use Framework\Base\Application\Exception\GuzzleHttpException;
use Framework\Base\Application\Exception\MethodNotAllowedException;
use Framework\Base\Application\RegistryInterface;
use Framework\Base\Logger\Log;
use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\MemoryLogger;
use Framework\Base\Render\RenderInterface;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Response\ResponseInterface;
use Framework\Base\Router\DispatcherInterface;
use Framework\Base\Sentry\SentryLogger;
use Framework\Base\Test\Dummies\DummyController;
use Framework\Base\Test\Dummies\DummyDispatcher;
use Framework\Base\Test\Dummies\DummyHttpClient;
use Framework\Base\Test\Dummies\DummyListener;
use Framework\Base\Test\Dummies\DummyRequest;
use Framework\Base\Test\Dummies\DummyResponse;
use Framework\Base\Test\Dummies\MemoryRenderer;
use Framework\Base\Test\Dummies\SampleService;
use Framework\Base\Test\UnitTest;

/**
 * Class BaseApplicationTest
 * @package Framework\Base\Test\Application
 */
class BaseApplicationTest extends UnitTest
{
    /**
     * test constructor, exception handler setter and getter, configuration setter and getter,
     * rootPath setter and getter, bootstrap() and services registry getter
     */
    public function testConstructorAndBootstrap()
    {
        $app = $this->getApplication();

        $this::assertInstanceOf(ExceptionHandler::class, $app->getExceptionHandler());
        $this::assertInstanceOf(ApplicationConfiguration::class, $app->getConfiguration());
        $this::assertEquals(realpath(dirname(__FILE__, 6)), $app->getRootPath());
        $this::assertInstanceOf(RegistryInterface::class, $app->getServicesRegistry());

    }

    public function testRun()
    {
        $app = $this->getApplication();
        $dispatcher = (new DummyDispatcher())
            ->setHandler('\Framework\Base\Test\Dummies\DummyController::testRun');

        $app->setDispatcher($dispatcher)
            ->setRenderer(new MemoryRenderer())
            ->run();

        $this::assertEquals(
            'RequestParsed',
            $this->getApplication()
                 ->getDispatcher()
                 ->getParseRequestResponse()
        );

        $this::assertEquals(
            'Finished',
            $app->getResponse()
                ->getBody()
        );

        $this::assertInstanceOf(
            ResponseInterface::class,
            $app->getRenderer()
                ->getResponse()
        );
    }

    public function testRunException()
    {
        $app = $this->getApplication();
        $dispatcher = (new DummyDispatcher())
            ->setHandler('\Framework\Base\Test\Dummies\DummyController::testRunFail');

        $app->setDispatcher($dispatcher)
            ->setRenderer(new MemoryRenderer())
            ->listen(
                ExceptionHandler::EVENT_EXCEPTION_HANDLER_HANDLE_PRE,
                DummyListener::class
            )
            ->run();

        $e = $app->getExceptionHandler()
                 ->getException();

        $this::assertEquals(
            $e->getMessage(),
            'Method testRunFail does not exist in \Framework\Base\Test\Dummies\DummyController. Check Your config file.'
        );

        $this::assertInstanceOf(
            DummyListener::class,
            $app->getResponse()
                ->getBody()
        );
    }

    public function testHandle()
    {
        $dispatcher = new DummyDispatcher();
        $dispatcher->setHandler('\Framework\Base\Test\Dummies\DummyController::testHandle');

        $this->getApplication()
             ->setDispatcher($dispatcher);

        $this::assertEquals(
            'Handled',
            $this->getApplication()
                 ->handle()
                 ->getBody()
        );
    }

    public function testParseRequest()
    {
        $this->getApplication()
             ->setDispatcher(new DummyDispatcher())
             ->parseRequest(new DummyRequest());

        $this::assertEquals(
            'Registered',
            $this->getApplication()
                 ->getDispatcher()
                 ->getRegisterResponse()
        );

        $this::assertEquals(
            'RequestParsed',
            $this->getApplication()
                 ->getDispatcher()
                 ->getParseRequestResponse()
        );
    }

    public function testHttpRequest()
    {
        $this->getApplication()
             ->setHttpClient(new DummyHttpClient())
             ->httpRequest('get');

        $this::assertEquals(
            ['body'],
            $this->getApplication()
                 ->getResponse()
                 ->getBody()
        );

        $this::assertEquals(
            1,
            $this->getApplication()
                 ->getResponse()
                 ->getCode()
        );

        $this::expectException(MethodNotAllowedException::class);
        $this::expectExceptionMessage('Http method not allowed');

        $this->getApplication()
             ->httpRequest('wrong');
    }

    public function testHttpRequestGuzzleException()
    {
        $this::expectException(GuzzleHttpException::class);
        $this::expectExceptionMessage('phrase');
        $this::expectExceptionCode(505);

        $this->getApplication()
             ->setHttpClient(new DummyHttpClient())
             ->httpRequest('get', 'exception');
    }

    public function testEventsAndListeners()
    {
        $app = $this->getApplication();

        $app->listen(
            BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE,
            DummyListener::class
        );

        $this::assertArrayHasKey(
            BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE,
            $app->getEvents()
        );

        $this::assertEquals(
            DummyListener::class,
            $app->getEvents()[BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE][0]
        );

        $this::assertInstanceOf(
            DummyListener::class,
            $app->triggerEvent(BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE)[0]
        );

        $app->removeEventListeners(BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE);

        $this::assertEmpty($app->getEvents());

        $app->listen(
            BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE,
            DummyController::class
        );

        $this::expectException(\RuntimeException::class);
        $this::expectExceptionMessage('Listeners "' . DummyController::class .'" must implement ListenerInterface.');

        $app->triggerEvent(BaseApplication::EVENT_APPLICATION_HANDLE_REQUEST_PRE);
    }

    public function testServicesSG()
    {
        $this->getApplication()
             ->registerService(new SampleService());

        $this::assertInstanceOf(
            SampleService::class,
            $this->getApplication()
                 ->getService(SampleService::class)
        );
    }

    public function testControllerSG()
    {
        $this->getApplication()
             ->setController(new DummyController());

        $this::assertInstanceOf(
            ControllerInterface::class,
            $this->getApplication()
                 ->getController()
        );
    }

    public function testDispatcherSG()
    {
        $this->getApplication()
             ->setDispatcher(new DummyDispatcher());

        $this::assertInstanceOf(
            DispatcherInterface::class,
            $this->getApplication()
                 ->getDispatcher()
        );
    }

    public function testRendererSG()
    {
        $this->getApplication()
             ->setRenderer(new MemoryRenderer());

        $this::assertInstanceOf(
            RenderInterface::class,
            $this->getApplication()
                 ->getRenderer()
        );
    }

    public function testRequestSG()
    {
        $this->getApplication()
             ->setRequest(new DummyRequest());

        $this::assertInstanceOf(
            RequestInterface::class,
            $this->getApplication()
                 ->getRequest()
        );
    }

    public function testResponseSG()
    {
        $this->getApplication()
             ->setResponse(new DummyResponse());

        $this::assertInstanceOf(
            ResponseInterface::class,
            $this->getApplication()
                 ->getResponse()
        );
    }

    /**
     *
     */
    public function testLoggers()
    {
        $payload = 'testPayload';
        $log = new Log($payload);
        $application = $this->getApplication();

        $this->assertAttributeCount(0, 'loggers', $application);


        $application->log($log);

        $this->assertContainsOnlyInstancesOf(MemoryLogger::class, $application->getLoggers());

        $this->assertAttributeCount(1, 'loggers', $application);


        $dsn = getenv('SENTRY_DSN');

        $sl = new SentryLogger();
        $sl->setClient($dsn, \Raven_Client::class);

        $application->addLogger($sl);

        $this->assertContainsOnlyInstancesOf(LoggerInterface::class, $application->getLoggers());

        $this->assertAttributeCount(2, 'loggers', $application);
    }

    /**
     *
     */
    public function testRootPath()
    {
        $path = realpath(
            dirname(__DIR__, 5)
        );

        $this::assertEquals(
            $path,
            $this->getApplication()
                 ->getConfiguration()
                 ->getPathValue('rootPath')
        );
    }
}
