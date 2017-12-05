<?php

namespace Framework\Base\Sentry;

use Framework\Base\Logger\LoggerInterface;
use Framework\Base\Logger\LogInterface;

/**
 * Class SentryLogger
 * @package Framework\Base
 */
class SentryLogger implements LoggerInterface
{
    /**
     * @var string
     */
    private $dsn = null;

    /**
     * @var \Raven_Client
     */
    private $client;

    /**
     * @param LogInterface $log
     *
     * @return null|string
     */
    public function log(LogInterface $log)
    {
        if ($log->isException()) {
            $event = $this->logException($log);
        } else {
            $event = $this->logMessage($log);
        }

        return $event;
    }

    /**
     * @param LogInterface $log
     *
     * @return null|string
     */
    private function logException(LogInterface $log)
    {
        $eventId = $this->getClient()
                        ->captureException(
                            $log->getPayload(),
                            $log->getAllData()
                        );

        return $eventId;
    }

    /**
     * @return \Raven_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param        $dsn
     * @param string $fullyClassifiedClassName
     *
     * @return LoggerInterface
     */
    public function setClient($dsn, string $fullyClassifiedClassName = \Raven_Client::class): LoggerInterface
    {
        $this->dsn = $dsn;
        $this->client = new $fullyClassifiedClassName($this->getDsn());
        $this->client->install();

        return $this;
    }

    /**
     * @param LogInterface $log
     *
     * @return null|string
     */
    private function logMessage(LogInterface $log)
    {
        $eventId = $this->getClient()
                        ->captureMessage(
                            $log->getPayload(),
                            [],
                            $log->getAllData()
                        );

        return $eventId;
    }

    /**
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }
}
