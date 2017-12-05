<?php

namespace Framework\Base\Application;

/**
 * Class BaseController
 * @package Framework\Base\Application
 */
abstract class BaseController implements ControllerInterface
{
    use ApplicationAwareTrait;

    /**
     * @param $fullyQualifiedClassName
     *
     * @return \Framework\Base\Repository\BrunoRepositoryInterface
     */
    public function getRepository($fullyQualifiedClassName)
    {
        return $this->getRepositoryManager()
                    ->getRepository($fullyQualifiedClassName);
    }

    /**
     * @return \Framework\Base\Manager\RepositoryManagerInterface
     * @throws \RuntimeException
     */
    public function getRepositoryManager()
    {
        if ($this->getApplication()->getRepositoryManager() === null) {
            throw new \RuntimeException('RepositoryManager manager not set');
        }

        return $this->getApplication()->getRepositoryManager();
    }

    /**
     * @param string $resourceName
     *
     * @return \Framework\Base\Repository\BrunoRepositoryInterface
     * @todo change method name
     */
    public function getRepositoryFromResourceName(string $resourceName)
    {
        return $this->getRepositoryManager()
                    ->getRepositoryFromResourceName($resourceName);
    }
}
