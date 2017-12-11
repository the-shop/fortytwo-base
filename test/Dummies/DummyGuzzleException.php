<?php

namespace Framework\Base\Test\Dummies;

use GuzzleHttp\Exception\GuzzleException;

class DummyGuzzleException extends \RuntimeException implements GuzzleException
{
    public function hasResponse()
    {
        return true;
    }

    public function getResponse()
    {
        return $this;
    }

    public function getStatusCode()
    {
        return 505;
    }

    public function getReasonPhrase()
    {
        return 'phrase';
    }
}
