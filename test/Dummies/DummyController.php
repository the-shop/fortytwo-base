<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\BaseController;

class DummyController extends BaseController
{
    public function testHandle()
    {
        return 'Handled';
    }

    public function testRun()
    {
        return 'Finished';
    }
}
