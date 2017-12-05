<?php

namespace Framework\Base\Logger;

/**
 * Class MemoryLogger
 * @package Framework\Base\Logger
 */
class MemoryLogger implements LoggerInterface
{
    /**
     * @var array
     */
    private $logs = [];

    /**
     * @param LogInterface $log
     *
     * @return $this
     */
    public function log(LogInterface $log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }
}
