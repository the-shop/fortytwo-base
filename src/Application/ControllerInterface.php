<?php

namespace Framework\Base\Application;

use Framework\Base\Manager\RepositoryManagerInterface;
use Framework\Base\Repository\BrunoRepositoryInterface;

/**
 * Interface ControllerInterface
 * @package Framework\Base\Application
 */
interface ControllerInterface extends ApplicationAwareInterface
{
    /**
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager();

    /**
     * @param $fullyQualifiedClassName
     *
     * @return BrunoRepositoryInterface
     */
    public function getRepository($fullyQualifiedClassName);
}
