<?php

namespace Framework\Base\Repository;

use Framework\Base\Application\ApplicationAwareInterface;
use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Database\DatabaseQueryInterface;
use Framework\Base\Manager\RepositoryManagerInterface;
use Framework\Base\Model\BrunoInterface;

/**
 * Interface BrunoRepositoryInterface
 * @package Framework\Base\Repository
 */
interface BrunoRepositoryInterface extends ApplicationAwareInterface
{
    /**
     * @param string $collection
     *
     * @return BrunoRepositoryInterface;
     */
    public function setCollection(string $collection): BrunoRepositoryInterface;

    /**
     * @return string
     */
    public function getCollection(): string;

    /**
     * @return DatabaseAdapterInterface[]
     */
    public function getDatabaseAdapters(): array;

    /**
     * @param RepositoryManagerInterface $repositoryManager
     *
     * @return BrunoRepositoryInterface
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager): BrunoRepositoryInterface;

    /**
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager(): RepositoryManagerInterface;

    /**
     * @return BrunoInterface
     */
    public function newModel(): BrunoInterface;

    /**
     * @param $identifier
     *
     * @return BrunoInterface|null
     */
    public function loadOne($identifier);

    /**
     * @param array $keyValues
     *
     * @return BrunoInterface|null
     */
    public function loadOneBy(array $keyValues);

    /**
     * @param $identifiers
     *
     * @return BrunoInterface[]
     */
    public function loadMultiple($identifiers = []);

    /**
     * @param BrunoInterface $bruno
     *
     * @return BrunoRepositoryInterface
     */
    public function save(BrunoInterface $bruno): BrunoRepositoryInterface;

    /**
     * @return DatabaseAdapterInterface
     */
    public function getPrimaryAdapter(): DatabaseAdapterInterface;

    /**
     * @return string
     */
    public function getModelPrimaryKey(): string;

    /**
     * @param BrunoInterface $model
     *
     * @return DatabaseQueryInterface
     */
    public function createNewQueryForModel(BrunoInterface $model): DatabaseQueryInterface;
}
