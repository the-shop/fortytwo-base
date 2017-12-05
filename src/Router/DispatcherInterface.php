<?php

namespace Framework\Base\Router;

use Framework\Base\Application\ApplicationAwareInterface;
use Framework\Base\Request\RequestInterface;

/**
 * Interface DispatcherInterface
 * @package Framework\Base\Router
 */
interface DispatcherInterface extends ApplicationAwareInterface
{
    /**
     * @return mixed
     */
    public function register();

    /**
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function parseRequest(RequestInterface $request);

    /**
     * @return string
     */
    public function getHandler(): string;

    /**
     * @return array
     */
    public function getRoutes(): array;

    /**
     * @return array
     */
    public function getRouteParameters(): array;

    /**
     * @param array $routesDefinition
     *
     * @return DispatcherInterface
     */
    public function addRoutes(array $routesDefinition = []): DispatcherInterface;
}
