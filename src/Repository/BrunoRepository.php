<?php

namespace Framework\Base\Repository;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Database\DatabaseQueryInterface;
use Framework\Base\Manager\RepositoryManagerInterface;
use Framework\Base\Model\BrunoInterface;

/**
 * Class BrunoRepository
 * @package Framework\Base\Repository
 */
abstract class BrunoRepository implements BrunoRepositoryInterface
{
    use ApplicationAwareTrait;

    /**
     * @var string
     */
    protected $collection = 'generic';

    /**
     * @var RepositoryManagerInterface|null
     */
    private $repositoryManager = null;

    /**
     * @var array
     */
    private $modelAttributesDefinition = [];

    /**
     * @return DatabaseAdapterInterface[]
     */
    public function getDatabaseAdapters(): array
    {
        return $this->getRepositoryManager()
                    ->getModelAdapters($this->collection);
    }

    /**
     * @return RepositoryManagerInterface|null
     */
    public function getRepositoryManager(): RepositoryManagerInterface
    {
        return $this->repositoryManager;
    }

    /**
     * @param RepositoryManagerInterface $repositoryManager
     *
     * @return BrunoRepositoryInterface
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager): BrunoRepositoryInterface
    {
        $this->repositoryManager = $repositoryManager;

        return $this;
    }

    /**
     * @param $identifier
     *
     * @return BrunoInterface|null
     */
    public function loadOne($identifier)
    {
        $model = $this->newModel();
        $adapter = $this->getPrimaryAdapter();

        if ($identifier instanceof DatabaseQueryInterface === false) {
            $query = $this->createNewQueryForModel($model);
            $query->addAndCondition(
                $model->getPrimaryKey(),
                '=',
                $identifier
            );
        } else {
            $query = $identifier;
        }

        $attributes = $adapter
            ->loadOne($query);

        if ($attributes === null) {
            return null;
        }

        $model->disableFieldFilters()
              ->setAttributes($attributes)
              ->setDatabaseAttributes($attributes)
              ->enableFieldFilters()
              ->setIsNew(false);

        return $model;
    }

    /**
     * @return BrunoInterface
     */
    public function newModel(): BrunoInterface
    {
        $modelClass = $this->getModelClassName();

        $modelAttributesDefinition = $this->getModelAttributesDefinition();

        /* @var BrunoInterface $model */
        $model = new $modelClass();

        $config = $this->getApplication()
                       ->getConfiguration();

        $model->defineModelAttributes($modelAttributesDefinition)
              ->setPrimaryKey($this->getModelPrimaryKey())
              ->setCollection($this->collection)
              ->setApplication($this->getApplication())
              ->setRepository($this)
              ->setDatabaseAddress($config->getPathValue('env.DATABASE_ADDRESS'))
              ->setDatabase($config->getPathValue('env.DATABASE_NAME'));

        return $model;
    }

    /**
     * @return string
     */
    public function getModelClassName(): string
    {
        $repositoryClass = get_class($this);

        return $this->getRepositoryManager()
                    ->getModelClass($repositoryClass);
    }

    /**
     * @return array
     */
    public function getModelAttributesDefinition(): array
    {
        return $this->modelAttributesDefinition;
    }

    /**
     * @return string
     */
    public function getModelPrimaryKey(): string
    {
        $modelAttDefinition = $this->getModelAttributesDefinition();

        foreach ($modelAttDefinition as $field => $definition) {
            if (isset($definition['primaryKey']) === true
                && $definition['primaryKey'] === true
            ) {
                return $field;
            }
        }

        return null;
    }

    /**
     * @return DatabaseAdapterInterface
     */
    public function getPrimaryAdapter(): DatabaseAdapterInterface
    {
        return $this->getRepositoryManager()
                    ->getPrimaryAdapter($this->collection);
    }

    /**
     * @param BrunoInterface $bruno
     *
     * @return DatabaseQueryInterface
     */
    public function createNewQueryForModel(BrunoInterface $bruno): DatabaseQueryInterface
    {
        $query = $this->getPrimaryAdapter()
                      ->newQuery();

        $query->setDatabase($bruno->getDatabase());
        $query->setCollection($this->getCollection());

        return $query;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * Sets `$collection` as the document collection
     *
     * @param string $collection
     *
     * @return BrunoRepositoryInterface
     */
    public function setCollection(string $collection): BrunoRepositoryInterface
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @param array $keyValues
     *  Example:
     *      $keyValues = [
     *          'name' = 'Name',
     *          'role' = 'Role',
     *          'xp' = [
     *              '>',
     *              1000,
     *          ];
     *      ];
     *
     * @return BrunoInterface|null
     */
    public function loadOneBy(array $keyValues = [])
    {
        $model = $this->newModel();
        $query = $this->createNewQueryForModel($model);

        foreach ($keyValues as $key => $identifier) {
            $condition = '=';
            if (is_array($identifier)) {
                list($condition, $value) = $identifier;
            } else {
                $value = $identifier;
            }
            $query->addAndCondition($key, $condition, $value);
        }

        $attributes = $this->getPrimaryAdapter()
                           ->loadOne($query);

        if ($attributes === null) {
            return null;
        }

        $model->disableFieldFilters();
        $model->setAttributes($attributes);
        $model->setDatabaseAttributes($attributes);
        $model->enableFieldFilters();
        $model->setIsNew(false);

        return $model;
    }

    /**
     * @param $identifiers
     *
     * @return BrunoInterface[]
     */
    public function loadMultiple($identifiers = []): array
    {
        $model = $this->newModel();
        $adapter = $this->getPrimaryAdapter();
        $out = [];

        if ($identifiers instanceof DatabaseQueryInterface === false) {
            $query = $this->createNewQueryForModel($model);
            foreach ($identifiers as $identifier => $value) {
                $query->addAndCondition($identifier, '=', $value);
            }
        } else {
            $query = $identifiers;
        }

        $data = $adapter
            ->loadMultiple($query);

        foreach ($data as $attributes) {
            $model = $this->newModel();
            $model->disableFieldFilters()
                  ->setAttributes($attributes)
                  ->setDatabaseAttributes($attributes)
                  ->enableFieldFilters()
                  ->setIsNew(false);

            $out[$model->getId()] = $model;
        }

        return $out;
    }

    /**
     * @param BrunoInterface $bruno
     *
     * @return BrunoRepositoryInterface
     * @throws \RuntimeException
     */
    public function save(BrunoInterface $bruno): BrunoRepositoryInterface
    {
        // TODO: Implement save() method, let bruno use that
        throw new \RuntimeException('Not implemented');

        return $bruno;
    }
}
