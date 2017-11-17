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
     * @return BrunoRepositoryInterface;
     */
    public function setCollection(string $collection);

    /**
     * @return string
     */
    public function getCollection();

    /**
     * @return DatabaseAdapterInterface|null
     */
    public function getDatabaseAdapters();

    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @return $this
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager);

    /**
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function newModel();

    /**
     * @param $identifier
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
     * @return BrunoInterface
     */
    public function save(BrunoInterface $bruno);

    /**
     * @return DatabaseAdapterInterface
     */
    public function getPrimaryAdapter();

    /**
     * @return string
     */
    public function getModelPrimaryKey();

    /**
     * @param BrunoInterface $model
     * @return DatabaseQueryInterface
     */
    public function createNewQueryForModel(BrunoInterface $model): DatabaseQueryInterface;
}
