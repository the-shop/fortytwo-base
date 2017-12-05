<?php

namespace Framework\Base\Manager;

use Framework\Base\Application\ApplicationAwareInterface;
use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Repository\BrunoRepositoryInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class RepositoryManager
 * @package Framework\Base\Manager
 */
class RepositoryManager implements RepositoryManagerInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var [string]
     */
    private $registeredRepositories = [];

    /**
     * @var [string]
     */
    private $registeredResources = [];

    /**
     * @var array
     */
    private $modelsToCollection = [];

    /**
     * @var [string]
     */
    private $registeredModelFields = [];

    /**
     * @var array
     */
    private $modelAdapters = [];

    /**
     * @var null
     */
    private $primaryAdapters = [];

    /**
     * @var array
     */
    private $authenticatableModels = [];

    /**
     * @param string $fullyQualifiedClassName
     *
     * @return BrunoRepositoryInterface
     * @throws \RuntimeException
     */
    public function getRepository(string $fullyQualifiedClassName = ''): BrunoRepositoryInterface
    {
        if (class_exists($fullyQualifiedClassName) === false) {
            throw new \RuntimeException('Model ' . $fullyQualifiedClassName . ' is not registered');
        }

        $repositoryClass = $this->registeredRepositories[$fullyQualifiedClassName];
        /* @var BrunoRepositoryInterface $repository */
        $repository = new $repositoryClass();
        $repository->setRepositoryManager($this)
                   ->setApplication($this->getApplication());

        return $repository;
    }

    /**
     * @param string $resourceName
     *
     * @return BrunoRepositoryInterface
     * @throws \RuntimeException
     */
    public function getRepositoryFromResourceName(string $resourceName): BrunoRepositoryInterface
    {
        if (isset($this->registeredResources[$resourceName]) === false) {
            throw new \RuntimeException(
                "Resource $resourceName not registered in Framework\Base\Manager\Repository"
            );
        }

        $repositoryClass = $this->registeredResources[$resourceName];
        /* @var BrunoRepositoryInterface $repository */
        $repository = new $repositoryClass();
        $repository->setRepositoryManager($this)
                   ->setCollection($resourceName)
                   ->setApplication($this->getApplication());

        return $repository;
    }

    /**
     * @param string $repositoryClass
     *
     * @return int|null|string
     * @throws \RuntimeException
     */
    public function getModelClass(string $repositoryClass): string
    {
        $foundClass = null;
        foreach ($this->registeredRepositories as $modelClass => $repoClass) {
            if ($repositoryClass === $repoClass) {
                $foundClass = $modelClass;
                break;
            }
        }

        if ($foundClass === null) {
            throw new \RuntimeException("Model class not registered for $repositoryClass");
        }

        return $foundClass;
    }

    /**
     * @param string $fullyQualifiedClassName
     *
     * @return RepositoryManagerInterface
     */
    public function registerRepository(string $fullyQualifiedClassName = ''): RepositoryManagerInterface
    {
        /**@todo unify implementation with `registerRepositories()` */

        array_push($this->registeredRepositories, $fullyQualifiedClassName);

        $this->registeredRepositories = array_unique($this->registeredRepositories, SORT_REGULAR);

        return $this;
    }

    /**
     * @param array $fullyQualifiedClassNames
     *
     * @return RepositoryManagerInterface
     */
    public function registerRepositories(array $fullyQualifiedClassNames = []): RepositoryManagerInterface
    {
        $this->registeredRepositories = ArrayUtils::merge($this->registeredRepositories, $fullyQualifiedClassNames);

        /** @todo check if zend `merge` works on multidimensional arrays */

        return $this;
    }

    /**
     * @param array $modelClassNameToCollection
     *
     * @return RepositoryManagerInterface
     */
    public function registerModelsToCollection(array $modelClassNameToCollection): RepositoryManagerInterface
    {
        $this->modelsToCollection = $modelClassNameToCollection;

        return $this;
    }

    /**
     * @param array $resourcesMap
     *
     * @return RepositoryManagerInterface
     */
    public function registerResources(array $resourcesMap = []): RepositoryManagerInterface
    {
        $this->registeredResources = ArrayUtils::merge($this->registeredResources, $resourcesMap);

        /** @todo check if zend `merge` works on multidimensional arrays */

        foreach ($resourcesMap as $resourceName => $repository) {
            if (isset($this->primaryAdapters[$resourceName]) === false) {
                $adapters = $this->getModelAdapters($resourceName);
                $this->setPrimaryAdapter($resourceName, reset($adapters));
            }
        }

        return $this;
    }

    /**
     * @param string $modelClassName
     *
     * @return DatabaseAdapterInterface[]
     * @throws \RuntimeException
     */
    public function getModelAdapters(string $modelClassName): array
    {
        if (isset($this->modelAdapters[$modelClassName]) === false) {
            throw new \RuntimeException("No registered adapters for $modelClassName");
        }

        return $this->modelAdapters[$modelClassName];
    }

    /**
     * @param string                   $modelClassName
     * @param DatabaseAdapterInterface $adapter
     *
     * @return RepositoryManagerInterface
     */
    public function setPrimaryAdapter(
        string $modelClassName,
        DatabaseAdapterInterface $adapter
    ): RepositoryManagerInterface
    {
        $this->primaryAdapters[$modelClassName] = $adapter;

        return $this;
    }

    /**
     * @param array $modelFieldsMap
     *
     * @return RepositoryManagerInterface
     */
    public function registerModelFields(array $modelFieldsMap = []): RepositoryManagerInterface
    {
        $this->registeredModelFields = ArrayUtils::merge($this->registeredModelFields, $modelFieldsMap);

        /** @todo check if zend `merge` works on multidimensional arrays */

        return $this;
    }

    /**
     * @param string $resourceName
     *
     * @return array
     * @throws \RuntimeException
     */
    public function getRegisteredModelFields(string $resourceName): array
    {
        if (isset($this->registeredModelFields[$resourceName]) === false) {
            throw new \RuntimeException("Model fields definition missing for model name: $resourceName");
        }

        return $this->registeredModelFields[$resourceName];
    }

    /**
     * @param string                   $modelClassName
     * @param DatabaseAdapterInterface $adapter
     *
     * @return RepositoryManagerInterface
     */
    public function addModelAdapter(
        string $modelClassName,
        DatabaseAdapterInterface $adapter
    ): RepositoryManagerInterface
    {
        $this->modelAdapters[$modelClassName][] = $adapter;

        return $this;
    }

    /**
     * @param string $modelClassName
     *
     * @return DatabaseAdapterInterface
     * @throws \RuntimeException
     */
    public function getPrimaryAdapter(string $modelClassName): DatabaseAdapterInterface
    {
        if (isset($this->primaryAdapters[$modelClassName]) === false) {
            throw new \RuntimeException("No registered primary adapter for $modelClassName");
        }

        return $this->primaryAdapters[$modelClassName];
    }

    /**
     * @param array $modelsConfigs
     *
     * @return RepositoryManagerInterface
     */
    public function addAuthenticatableModels(array $modelsConfigs = []): RepositoryManagerInterface
    {
        foreach ($modelsConfigs as $modelName => $params) {
            $this->addAuthenticatableModel($modelName, $params);
        }

        return $this;
    }

    /**
     * @param string $modelName
     * @param array  $params
     *
     * @return RepositoryManagerInterface
     */
    public function addAuthenticatableModel(string $modelName, array $params = []): RepositoryManagerInterface
    {
        $this->authenticatableModels[$modelName] = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getAuthenticatableModels(): array
    {
        return $this->authenticatableModels;
    }
}
