<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Service\Service;

/**
 * Class SampleService
 * @package Framework\Base\Test\Dummies
 */
class SampleService extends Service
{
    use ApplicationAwareTrait;

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }
}
