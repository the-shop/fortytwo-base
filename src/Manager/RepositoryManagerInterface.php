<?php

namespace Framework\Base\Manager;

use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Repository\BrunoRepositoryInterface;

/**
 * Interface RepositoryManagerInterface
 * @package Framework\Base\Manager
 */
interface RepositoryManagerInterface
{
    /**
     * @param string $fullyQualifiedClassName
     *
     * @return BrunoRepositoryInterface
     */
    public function getRepository(string $fullyQualifiedClassName = ''): BrunoRepositoryInterface;

    /**
     * @param string $resourceName
     *
     * @return BrunoRepositoryInterface
     * @todo change method name
     */
    public function getRepositoryFromResourceName(string $resourceName): BrunoRepositoryInterface;

    /**
     * @param string $fullyQualifiedClassName
     *
     * @return RepositoryManagerInterface
     */
    public function registerRepository(string $fullyQualifiedClassName = ''): RepositoryManagerInterface;

    /**
     * @param array $fullyQualifiedClassNames
     *
     * @return RepositoryManagerInterface
     */
    public function registerRepositories(array $fullyQualifiedClassNames = []): RepositoryManagerInterface;

    /**
     * @param array $resourcesMap
     *
     * @return RepositoryManagerInterface
     * @todo change method name
     */
    public function registerResources(array $resourcesMap = []): RepositoryManagerInterface;

    /**
     * @param array $modelFieldsMap
     *
     * @return RepositoryManagerInterface
     */
    public function registerModelFields(array $modelFieldsMap = []): RepositoryManagerInterface;

    /**
     * @param string $modelName
     *
     * @return array
     */
    public function getRegisteredModelFields(string $modelName): array;

    /**
     * @param string $repositoryClass
     *
     * @return string
     */
    public function getModelClass(string $repositoryClass): string;

    /**
     * @param string                   $modelClassName
     * @param DatabaseAdapterInterface $adapter
     *
     * @return RepositoryManagerInterface
     */
    public function addModelAdapter(
        string $modelClassName,
        DatabaseAdapterInterface $adapter
    ): RepositoryManagerInterface;

    /**
     * @param string $modelClassName
     *
     * @return DatabaseAdapterInterface[]
     */
    public function getModelAdapters(string $modelClassName): array;

    /**
     * @param array $modelClassNameToCollection
     *
     * @return RepositoryManagerInterface
     */
    public function registerModelsToCollection(array $modelClassNameToCollection): RepositoryManagerInterface;

    /**
     * @param string                   $modelClassName
     * @param DatabaseAdapterInterface $adapter
     *
     * @return RepositoryManagerInterface
     */
    public function setPrimaryAdapter(
        string $modelClassName,
        DatabaseAdapterInterface $adapter
    ): RepositoryManagerInterface;

    /**
     * @param string $modelClassName
     *
     * @return DatabaseAdapterInterface
     */
    public function getPrimaryAdapter(string $modelClassName): DatabaseAdapterInterface;

    /**
     * @param array $modelsConfigs
     *
     * @return RepositoryManagerInterface
     */
    public function addAuthenticatableModels(array $modelsConfigs): RepositoryManagerInterface;

    /**
     * @param string $modelName
     * @param array  $params
     *
     * @return RepositoryManagerInterface
     */
    public function addAuthenticatableModel(string $modelName, array $params): RepositoryManagerInterface;

    /**
     * @return array
     */
    public function getAuthenticatableModels(): array;
}
