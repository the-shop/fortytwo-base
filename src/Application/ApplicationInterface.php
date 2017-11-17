<?php

namespace Framework\Base\Application;

use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\LogInterface;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Response\ResponseInterface;

/**
 * Interface ApplicationInterface
 * @package Framework\Base\Application
 */
interface ApplicationInterface
{
    /**
     * @return ResponseInterface
     */
    public function handle();

    /**
     * @return \Framework\Base\Manager\RepositoryManagerInterface|null
     */
    public function getRepositoryManager();

    /**
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function setResponse(ResponseInterface $response);

    /**
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * @return \Framework\Base\Router\DispatcherInterface
     */
    public function getDispatcher();

    /**
     * @param ControllerInterface $controller
     * @return ApplicationInterface
     */
    public function setController(ControllerInterface $controller);

    /**
     * @return ControllerInterface|null
     */
    public function getController();

    /**
     * @param string $eventName
     * @param mixed $payload
     * @return mixed
     */
    public function triggerEvent(string $eventName, $payload = null);

    /**
     * @param string $eventName
     * @param string $listenerClass
     * @return mixed
     */
    public function listen(string $eventName, string $listenerClass);

    /**
     * @param string $eventName
     * @return mixed
     */
    public function removeEventListeners(string $eventName);

    /**
     * @return mixed
     */
    public function removeAllEventListeners();

    /**
     * @param LoggerInterface $logger
     *
     * @return \Framework\Base\Application\ApplicationInterface
     */
    public function addLogger(LoggerInterface $logger): ApplicationInterface;

    /**
     * @param LogInterface $log
     *
     * @return \Framework\Base\Application\ApplicationInterface
     */
    public function log(LogInterface $log): ApplicationInterface;

    /**
     * @return LoggerInterface[]
     */
    public function getLoggers();

    /**
     * @param string $serviceClass
     * @return mixed
     */
    public function getService(string $serviceClass);

    /**
     * @return array
     */
    public function getEvents(): array;

    /**
     * @param ServiceInterface $service
     * @param bool $overwriteExisting
     * @return ApplicationInterface
     */
    public function registerService(ServiceInterface $service, bool $overwriteExisting = false);

    /**
     * @return \Framework\Base\Application\ConfigurationInterface
     */
    public function getConfiguration();

    /**
     * Curl Request method
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     *
     * @throws \RuntimeException
     * @throws \Framework\Base\Application\Exception\GuzzleHttpException
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function httpRequest(string $method, string $uri = '', array $params = []);
}
