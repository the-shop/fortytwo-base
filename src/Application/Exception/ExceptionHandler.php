<?php

namespace Framework\Base\Application\Exception;

use Framework\Base\Application\ApplicationAwareInterface;
use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Logger\Log;

/**
 * Class ExceptionHandler
 * @package Framework\Base
 */
class ExceptionHandler implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    private $exception = null;

    /**
     * @const string
     */
    const EVENT_EXCEPTION_HANDLER_HANDLE_PRE = 'EVENT\EXCEPTION_HANDLER\HANDLE_PRE';

    /**
     * @const string
     */
    const EVENT_EXCEPTION_HANDLER_HANDLE_POST = 'EVENT\EXCEPTION_HANDLER\HANDLE_POST';

    /**
     * @param \Exception $e
     *
     * @return $this
     */
    public function handle(\Exception $e)
    {
        $application = $this->getApplication();
        $application->triggerEvent(self::EVENT_EXCEPTION_HANDLER_HANDLE_PRE, $e);

        $errorLog = new Log($e);

        $application->log($errorLog);

        $this->exception = $e;

        $application->triggerEvent(self::EVENT_EXCEPTION_HANDLER_HANDLE_POST, $e);

        return $this;
    }

    public function getException()
    {
        return $this->exception;
    }
}
