<?php

namespace Framework\Base\Application;

use Framework\Base\Application\Exception\ExceptionHandler;
use Framework\Base\Application\Exception\GuzzleHttpException;
use Framework\Base\Application\Exception\MethodNotAllowedException;
use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\LogInterface;
use Framework\Base\Manager\RepositoryManagerInterface;
use Framework\Base\Render\RenderInterface;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Response\ResponseInterface;
use Framework\Base\Router\DispatcherInterface;
use Framework\Base\Service\ServiceInterface;

/**
 * Interface ApplicationInterface
 * @package Framework\Base\Application
 */
interface ApplicationInterface
{
    /**
     * @return RequestInterface
     */
    public function buildRequest();

    /**
     * @return ApplicationInterface
     */
    public function run(): ApplicationInterface;

    /**
     * @return Bootstrap
     */
    public function bootstrap(): Bootstrap;

    /**
     * @param string $eventName
     * @param null   $payload
     *
     * @return array
     */
    public function triggerEvent(string $eventName, $payload = null): array;

    /**
     * @return ResponseInterface
     */
    public function handle();

    /**
     * @param RequestInterface $request
     *
     * @return ApplicationInterface
     */
    public function parseRequest(RequestInterface $request): ApplicationInterface;

    /**
     * Curl Request method
     *
     * @param string $method
     * @param string $uri
     * @param array  $params
     *
     * @throws MethodNotAllowedException
     * @throws GuzzleHttpException
     * @return ResponseInterface
     */
    public function httpRequest(string $method, string $uri = '', array $params = []): ResponseInterface;

    /**
     * @param string $eventName
     * @param string $listenerClass
     *
     * @return ApplicationInterface
     */
    public function listen(string $eventName, string $listenerClass): ApplicationInterface;

    /**
     * @param LogInterface $log
     *
     * @return ApplicationInterface
     */
    public function log(LogInterface $log): ApplicationInterface;

    /**
     * @param string $eventName
     *
     * @return ApplicationInterface
     */
    public function removeEventListeners(string $eventName): ApplicationInterface;

    /**
     * @return ApplicationInterface
     */
    public function removeAllEventListeners(): ApplicationInterface;

    /**
     * @return ApplicationConfigurationInterface
     */
    public function getConfiguration(): ApplicationConfigurationInterface;

    /**
     * @return array
     */
    public function getEvents(): array;

    /**
     * @param string $serviceClass
     *
     * @return ServiceInterface
     */
    public function getService(string $serviceClass): ServiceInterface;

    /**
     * @param ServiceInterface $service
     * @param bool             $overwriteExisting
     *
     * @return ApplicationInterface
     */
    public function registerService(ServiceInterface $service, bool $overwriteExisting = false);

    /**
     * @return LoggerInterface[]
     */
    public function getLoggers(): array;

    /**
     * @param LoggerInterface $logger
     *
     * @return ApplicationInterface
     */
    public function addLogger(LoggerInterface $logger): ApplicationInterface;

    /**
     * @return ControllerInterface
     */
    public function getController(): ControllerInterface;

    /**
     * @param ControllerInterface $controller
     *
     * @return ApplicationInterface
     */
    public function setController(ControllerInterface $controller): ApplicationInterface;

    /**
     * @return DispatcherInterface
     */
    public function getDispatcher(): DispatcherInterface;

    /**
     * @param DispatcherInterface $dispatcher
     *
     * @return ApplicationInterface
     */
    public function setDispatcher(DispatcherInterface $dispatcher): ApplicationInterface;

    /**
     * @return ExceptionHandler
     */
    public function getExceptionHandler(): ExceptionHandler;

    /**
     * @param ExceptionHandler $exceptionHandler
     *
     * @return ApplicationInterface
     */
    public function setExceptionHandler(ExceptionHandler $exceptionHandler): ApplicationInterface;

    /**
     * @return mixed
     */
    public function getHttpClient();

    /**
     * @param $client
     *
     * @return ApplicationInterface
     */
    public function setHttpClient($client): ApplicationInterface;

    /**
     * @return RenderInterface
     */
    public function getRenderer(): RenderInterface;

    /**
     * @param RenderInterface $render
     *
     * @return ApplicationInterface
     */
    public function setRenderer(RenderInterface $render): ApplicationInterface;

    /**
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager(): RepositoryManagerInterface;

    /**
     * @param RepositoryManagerInterface $repositoryManager
     *
     * @return ApplicationInterface
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager): ApplicationInterface;

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * @param RequestInterface $request
     *
     * @return ApplicationInterface
     */
    public function setRequest(RequestInterface $request): ApplicationInterface;

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;

    /**
     * @param ResponseInterface $response
     *
     * @return ApplicationInterface
     */
    public function setResponse(ResponseInterface $response): ApplicationInterface;

    /**
     * @return string
     */
    public function getRootPath(): string;

    /**
     * @param string $path
     *
     * @return ApplicationInterface
     */
    public function setRootPath(string $path): ApplicationInterface;

    /**
     * @return RegistryInterface
     */
    public function getServicesRegistry(): RegistryInterface;
}
