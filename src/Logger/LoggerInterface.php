<?php

namespace Framework\Base\Logger;

/**
 * Interface LoggerInterface
 * @package Framework\Base\Logger
 */
interface LoggerInterface
{
    /**
     * @param LogInterface $log
     *
     * @return mixed
     */
    public function log(LogInterface $log);
}
