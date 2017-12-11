<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Event\ListenerInterface;

class DummyListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    public function handle($payload)
    {
        $this->getApplication()
             ->getResponse()
             ->setBody($this);

        return $this;
    }
}
