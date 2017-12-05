<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationConfigurationInterface;
use Framework\Base\Application\BaseApplication;
use Framework\Base\Request\RequestInterface;

/**
 * Class DummyApplication
 * @package Framework\Base\Test\Dummies
 */
class DummyApplication extends BaseApplication
{
    /**
     * DummyApplication constructor.
     *
     * @param ApplicationConfigurationInterface|null $applicationConfiguration
     */
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration = null)
    {
        $this->setResponse(new DummyResponse());

        parent::__construct($applicationConfiguration);
    }

    /**
     * @return RequestInterface
     */
    public function buildRequest(): RequestInterface
    {
        $request = new DummyRequest();

        $this->setRequest($request);

        return $request;
    }
}
