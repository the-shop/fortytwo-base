<?php

namespace Framework\Base\Test\Dummies;

class DummyHttpClient
{
    public function request($one, $two = null, $three = null)
    {
        if ($two ===  'exception') {
            throw new DummyGuzzleException();
        }
        return $this;
    }

    public function getStatusCode()
    {
        return 1;
    }

    public function getBody()
    {
        return ['body'];
    }
}
