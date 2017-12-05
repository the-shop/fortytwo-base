<?php

namespace Framework\Base\Application\Exception;

/**
 * Class MethodNotAllowedException
 * @package Framework\Base
 */
class MethodNotAllowedException extends \Exception
{
    /**
     * @var array
     */
    private $allowedMethods = [];

    /**
     * @param array $methods
     *
     * @return $this
     */
    public function setAllowedMethods(array $methods = [])
    {
        $this->allowedMethods = $methods;

        return $this;
    }
}
