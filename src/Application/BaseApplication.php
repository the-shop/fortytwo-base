<?php

namespace Framework\Base\Application;

use Framework\Base\Application\Exception\ExceptionHandler;
use Framework\Base\Application\Exception\GuzzleHttpException;
use Framework\Base\Application\Exception\MethodNotAllowedException;
use Framework\Base\Event\ListenerInterface;
use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\LogInterface;
use Framework\Base\Logger\MemoryLogger;
use Framework\Base\Manager\RepositoryManager;
use Framework\Base\Manager\RepositoryManagerInterface;
use Framework\Base\Render\RenderInterface;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Response\ResponseInterface;
use Framework\Base\Router\DispatcherInterface;
use Framework\Base\Service\ServiceInterface;
use Framework\Base\Service\ServicesRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class BaseApplication
 * @package Framework\Base\Application
 */
abstract class BaseApplication implements ApplicationInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @const string
     */
    const EVENT_APPLICATION_BUILD_REQUEST_PRE = 'EVENT\APPLICATION\BUILD_REQUEST_PRE';

    /**
     * @const string
     */
    const EVENT_APPLICATION_BUILD_REQUEST_POST = 'EVENT\APPLICATION\BUILD_REQUEST_POST';

    /**
     * @const string
     */
    const EVENT_APPLICATION_PARSE_REQUEST_PRE = 'EVENT\APPLICATION\PARSE_REQUEST_PRE';

    /**
     * @const string
     */
    const EVENT_APPLICATION_PARSE_REQUEST_POST = 'EVENT\APPLICATION\PARSE_REQUEST_POST';

    /**
     * @const string
     */
    const EVENT_APPLICATION_HANDLE_REQUEST_PRE = 'EVENT\APPLICATION\HANDLE_REQUEST_PRE';

    /**
     * @const string
     */
    const EVENT_APPLICATION_HANDLE_REQUEST_POST = 'EVENT\APPLICATION\HANDLE_REQUEST_POST';

    /**
     * @const string
     */
    const EVENT_APPLICATION_RENDER_RESPONSE_PRE = 'EVENT\APPLICATION\RENDER_REQUEST_PRE';

    /**
     * @const string
     */
    const EVENT_APPLICATION_RENDER_RESPONSE_POST = 'EVENT\APPLICATION\RENDER_REQUEST_POST';

    /**
     * @var DispatcherInterface|null
     */
    private $dispatcher = null;

    /**
     * @var RequestInterface|null
     */
    private $request = null;

    /**
     * @var ResponseInterface|null
     */
    private $response = null;

    /**
     * @var ControllerInterface|null
     */
    private $controller = null;

    /**
     * @var RepositoryManagerInterface|null
     */
    private $repositoryManager = null;

    /**
     * @var ExceptionHandler|null
     */
    private $exceptionHandler = null;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var RenderInterface|null
     */
    private $renderer = null;

    /**
     * @var LoggerInterface[]
     */
    private $loggers = [];

    /**
     * @var \Framework\Base\Service\ServicesRegistry|null
     */
    private $servicesRegistry = null;

    /**
     * @var ApplicationConfiguration
     */
    private $configuration = null;

    /**
     * @var null
     */
    private $httpClient = null;

    /**
     * @var Bootstrap
     */
    private $bootstrap = null;

    /**
     * Has to build instance of RequestInterface, set it to BaseApplication and return it
     * @return RequestInterface
     */
    abstract public function buildRequest();

    /**
     * BaseApplication constructor.
     *
     * @param ApplicationConfigurationInterface|null $applicationConfiguration
     */
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration = null)
    {
        if ($applicationConfiguration === null) {
            $applicationConfiguration = new ApplicationConfiguration();
        }

        $this->configuration = $applicationConfiguration;

        $this->setRootPath()
             ->setExceptionHandler(new ExceptionHandler())
             ->initialize();
    }

    /**
     * Main application entry point
     * @return ApplicationInterface
     */
    public function run(): ApplicationInterface
    {
        try {
            $this->triggerEvent(self::EVENT_APPLICATION_BUILD_REQUEST_PRE);
            $request = $this->getRequest();
            $this->triggerEvent(self::EVENT_APPLICATION_BUILD_REQUEST_POST);

            $this->triggerEvent(self::EVENT_APPLICATION_PARSE_REQUEST_PRE);
            $this->parseRequest($request);
            $this->triggerEvent(self::EVENT_APPLICATION_PARSE_REQUEST_POST);

            $this->triggerEvent(self::EVENT_APPLICATION_HANDLE_REQUEST_PRE);
            $response = $this->handle();
            $this->triggerEvent(self::EVENT_APPLICATION_HANDLE_REQUEST_POST);

            $this->triggerEvent(self::EVENT_APPLICATION_RENDER_RESPONSE_PRE);
            $this->getRenderer()
                 ->render($response);
            $this->triggerEvent(self::EVENT_APPLICATION_RENDER_RESPONSE_POST);
        } catch (\Exception $e) {
            $this->getExceptionHandler()
                 ->handle($e);

            $this->getRenderer()
                 ->render($this->getResponse());
        }

        return $this;
    }

    /**
     *
     */
    public function initialize()
    {
        $this->servicesRegistry = new ServicesRegistry();
        $this->servicesRegistry->setApplication($this);

        $this->bootstrap = new Bootstrap();
        $registeredModules = $this->getConfiguration()
                                  ->getRegisteredModules();

        $this->bootstrap->loadModulesConfigs($registeredModules, $this);
    }

    /**
     * @return ApplicationInterface
     */
    public function bootstrap(): ApplicationInterface
    {
        $this->bootstrap->registerModules();

        return $this;
    }

    /**
     * @param string $eventName
     * @param null   $payload
     *
     * @return array
     * @throws \RuntimeException
     */
    public function triggerEvent(string $eventName, $payload = null): array
    {
        $responses = [];
        if (isset($this->events[$eventName]) === true) {
            foreach ($this->events[$eventName] as $listenerClass) {
                /**
                 * @var ListenerInterface $listener
                 */
                $listener = new $listenerClass();
                if (($listener instanceof ListenerInterface) === false) {
                    throw new \RuntimeException('Listeners "' . $listenerClass . '" must implement ListenerInterface.');
                }
                $listener->setApplication($this);
                $responses[] = $listener->handle($payload);
            }
        }

        return $responses;
    }

    /**
     * @return ResponseInterface
     * @throws \BadMethodCallException
     * @throws \RuntimeException
     */
    public function handle()
    {
        $dispatcher = $this->getDispatcher();

        $handlerPath = $dispatcher->getHandler();

        $handlerPathParts = explode('::', $handlerPath);

        list($controllerClass, $action) = $handlerPathParts;

        /* @var ControllerInterface $controller */
        $controller = new $controllerClass();

        if (($controller instanceof ControllerInterface) === false) {
            throw new \RuntimeException("$controllerClass must be instance of ControllerInterface");
        }
        $this->setController($controller);
        $controller->setApplication($this);

        $parameterValues = array_values(
            $this->getDispatcher()
                 ->getRouteParameters()
        );

        if (method_exists($controller, $action) === false) {
            throw new \BadMethodCallException(
                "Method $action does not exist in $controllerClass. Check Your config file."
            );
        }

        $handlerOutput = $controller->{$action}(...$parameterValues);

        $response = $this->getResponse();
        $response->setBody($handlerOutput);

        return $response;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ApplicationInterface
     */
    public function parseRequest(RequestInterface $request): ApplicationInterface
    {
        $dispatcher = $this->getDispatcher();
        $dispatcher->setApplication($this);
        $dispatcher->register();
        $dispatcher->parseRequest($request);

        return $this;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     *
     * @return ResponseInterface
     * @throws GuzzleHttpException
     * @throws MethodNotAllowedException
     */
    public function httpRequest(string $method, string $uri = '', array $params = []): ResponseInterface
    {
        $allowedHttpMethods = [
            'GET',
            'POST',
            'PUT',
            'DELETE',
            'PATCH',
            'HEAD',
            'OPTIONS'
        ];

        if (is_string($method) === false ||
            in_array(strtoupper($method), $allowedHttpMethods, true) === false
        ) {
            $exception = new MethodNotAllowedException('Http method not allowed');
            $exception->setAllowedMethods($allowedHttpMethods);
            throw $exception;
        }

        $client = $this->getHttpClient();

        try {
            $guzzleHttpResponse = $client->request($method, $uri, $params);
        } catch (GuzzleException $requestException) {
            if ($requestException->hasResponse() === false) {
                $message = $requestException->getMessage();
                $code = null;
            } else {
                $message = $requestException->getResponse()
                                            ->getReasonPhrase();

                $code = $requestException->getResponse()
                                         ->getStatusCode();
            }
            throw new GuzzleHttpException($message, $code);
        }

        $response = $this->getResponse()
                         ->setCode($guzzleHttpResponse->getStatusCode())
                         ->setBody($guzzleHttpResponse->getBody());

        return $response;
    }

    /**
     * @param string $eventName
     * @param string $listenerClass
     *
     * @return ApplicationInterface
     */
    public function listen(string $eventName, string $listenerClass): ApplicationInterface
    {
        if (isset($this->events[$eventName]) === false) {
            $this->events[$eventName] = [];
        }

        if (in_array($listenerClass, $this->events[$eventName]) === false) {
            $this->events[$eventName][] = $listenerClass;
        }

        return $this;
    }

    /**
     * @param LogInterface $log
     *
     * @return ApplicationInterface
     */
    public function log(LogInterface $log): ApplicationInterface
    {
        if (count($this->getLoggers()) === 0) {
            $this->addLogger(new MemoryLogger());
        }
        foreach ($this->getLoggers() as $logger) {
            $logger->log($log);
        }

        return $this;
    }

    /**
     * @param string $eventName
     *
     * @return ApplicationInterface
     */
    public function removeEventListeners(string $eventName): ApplicationInterface
    {
        if (isset($this->events[$eventName]) === true) {
            unset($this->events[$eventName]);
        }

        return $this;
    }

    /**
     * @return ApplicationInterface
     */
    public function removeAllEventListeners(): ApplicationInterface
    {
        $this->events = [];

        return $this;
    }

    /**
     * @return ApplicationConfigurationInterface
     */
    public function getConfiguration(): ApplicationConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param string $serviceClass
     *
     * @return ServiceInterface
     */
    public function getService(string $serviceClass): ServiceInterface
    {
        return $this->servicesRegistry->get($serviceClass);
    }

    /**
     * @param ServiceInterface $service
     * @param bool             $overwriteExisting
     *
     * @return ApplicationInterface
     */
    public function registerService(ServiceInterface $service, bool $overwriteExisting = false): ApplicationInterface
    {
        $this->servicesRegistry->registerService($service, $overwriteExisting);

        return $this;
    }

    /**
     * @return LoggerInterface[]
     */
    public function getLoggers(): array
    {
        return $this->loggers;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ApplicationInterface
     */
    public function addLogger(LoggerInterface $logger): ApplicationInterface
    {
        $this->loggers[] = $logger;

        return $this;
    }

    /**
     * @return ControllerInterface|null
     */
    public function getController(): ControllerInterface
    {
        return $this->controller;
    }

    /**
     * @param ControllerInterface $controller
     *
     * @return ApplicationInterface
     */
    public function setController(ControllerInterface $controller): ApplicationInterface
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return DispatcherInterface
     * @throws \RuntimeException
     */
    public function getDispatcher(): DispatcherInterface
    {
        if ($this->dispatcher === null) {
            throw new \RuntimeException('Dispatcher object not set.');
        }

        return $this->dispatcher;
    }

    /**
     * @param DispatcherInterface $dispatcher
     *
     * @return ApplicationInterface
     */
    public function setDispatcher(DispatcherInterface $dispatcher): ApplicationInterface
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * @return ExceptionHandler|null
     */
    public function getExceptionHandler(): ExceptionHandler
    {
        return $this->exceptionHandler;
    }

    /**
     * @param ExceptionHandler $exceptionHandler
     *
     * @return ApplicationInterface
     */
    public function setExceptionHandler(ExceptionHandler $exceptionHandler): ApplicationInterface
    {
        $this->exceptionHandler = $exceptionHandler;

        $this->exceptionHandler->setApplication($this);

        return $this;
    }

    /**
     * @return Client|null
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * @param $client
     *
     * @return ApplicationInterface
     */
    public function setHttpClient($client): ApplicationInterface
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * @return RenderInterface
     */
    public function getRenderer(): RenderInterface
    {
        return $this->renderer;
    }

    /**
     * @param RenderInterface $render
     *
     * @return ApplicationInterface
     */
    public function setRenderer(RenderInterface $render): ApplicationInterface
    {
        $this->renderer = $render;

        return $this;
    }

    /**
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager(): RepositoryManagerInterface
    {
        if ($this->repositoryManager === null) {
            $repository = new RepositoryManager();
            $repository->setApplication($this);
            $this->setRepositoryManager($repository);
        }

        return $this->repositoryManager;
    }

    /**
     * @param RepositoryManagerInterface $repositoryManager
     *
     * @return ApplicationInterface
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager): ApplicationInterface
    {
        $this->repositoryManager = $repositoryManager;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        if ($this->request === null) {
            $this->buildRequest();
        }

        return $this->request;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ApplicationInterface
     */
    public function setRequest(RequestInterface $request): ApplicationInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ApplicationInterface
     */
    public function setResponse(ResponseInterface $response): ApplicationInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->getConfiguration()
                    ->getPathValue('rootPath');
    }

    /**
     * @param string|null $path
     *
     * @return ApplicationInterface
     */
    public function setRootPath(string $path = null): ApplicationInterface
    {
        $this->getConfiguration()
             ->setPathValue('rootPath', realpath(dirname(__FILE__, 6)));

        return $this;
    }

    /**
     * @return RegistryInterface
     */
    public function getServicesRegistry(): RegistryInterface
    {
        return $this->servicesRegistry;
    }
}
