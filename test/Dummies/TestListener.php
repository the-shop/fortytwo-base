<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Event\ListenerInterface;

/**
 * Class TestListener
 * @package Framework\Base\Test\Dummies
 */
class TestListener implements ListenerInterface
{
    use ApplicationAwareTrait;

    /**
     * @param $payload
     *
     * @return mixed
     */
    public function handle($payload)
    {
        return $payload;
    }
}
