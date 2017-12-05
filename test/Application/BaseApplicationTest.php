<?php

namespace Framework\Base\Test\Application;

use Framework\Base\Logger\Log;
use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\MemoryLogger;
use Framework\Base\Sentry\SentryLogger;
use Framework\Base\Test\UnitTest;

/**
 * Class BaseApplicationTest
 * @package Framework\Base\Test\Application
 */
class BaseApplicationTest extends UnitTest
{
    /**
     *
     */
    public function testLoggers()
    {
        $payload = 'testPayload';
        $log = new Log($payload);
        $application = $this->getApplication();

        $this->assertAttributeCount(0, 'loggers', $application);


        $application->log($log);

        $this->assertContainsOnlyInstancesOf(MemoryLogger::class, $application->getLoggers());

        $this->assertAttributeCount(1, 'loggers', $application);


        $dsn = getenv('SENTRY_DSN');

        $sl = new SentryLogger();
        $sl->setClient($dsn, \Raven_Client::class);

        $application->addLogger($sl);

        $this->assertContainsOnlyInstancesOf(LoggerInterface::class, $application->getLoggers());

        $this->assertAttributeCount(2, 'loggers', $application);
    }

    /**
     *
     */
    public function testRootPath()
    {
        $path = realpath(
            dirname(__DIR__, 5)
        );

        $this::assertEquals(
            $path,
            $this->getApplication()
                 ->getConfiguration()
                 ->getPathValue('rootPath')
        );
    }
}
