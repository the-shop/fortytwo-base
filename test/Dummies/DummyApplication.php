<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationConfiguration;
use Framework\Base\Application\BaseApplication;

class DummyApplication extends BaseApplication
{
    public function __construct(ApplicationConfiguration $applicationConfiguration = null)
    {
        $this->setResponse(new DummyResponse());

        parent::__construct($applicationConfiguration);
    }

    public function buildRequest()
    {
        $request = new DummyRequest();

        $this->setRequest($request);

        return $request;
    }
}
