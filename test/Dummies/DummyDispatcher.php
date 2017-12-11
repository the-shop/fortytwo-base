<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Request\RequestInterface;
use Framework\Base\Router\DispatcherInterface;

class DummyDispatcher implements DispatcherInterface
{
    use ApplicationAwareTrait;

    private $routes = [];

    private $routeParams = [];

    private $handler;

    private $registerResponse;

    private $parseRequestResponse;

    public function register()
    {
        $this->registerResponse = 'Registered';
    }

    public function parseRequest(RequestInterface $request)
    {
        $this->parseRequestResponse = 'RequestParsed';
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRouteParameters(): array
    {
        return $this->routeParams;
    }

    public function addRoutes(array $routesDefinition = []): DispatcherInterface
    {
        $this->routes = $routesDefinition;

        return $this;
    }

    public function getRegisterResponse()
    {
        return $this->registerResponse;
    }

    public function getParseRequestResponse()
    {
        return $this->parseRequestResponse;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }
}
