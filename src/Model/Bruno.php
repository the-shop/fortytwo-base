<?php

namespace Framework\Base\Model;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Model\Modifiers\FieldModifierInterface;
use Framework\Base\Model\Modifiers\HashFilter;
use Framework\Base\Repository\BrunoRepositoryInterface;

/**
 * Base Model for database
 * @package Framework\Base\Model
 */
abstract class Bruno implements BrunoInterface
{
    use ApplicationAwareTrait;

    /**
     * @const string
     */
    const EVENT_MODEL_HANDLE_ATTRIBUTE_VALUE_MODIFY_PRE = 'EVENT\MODEL\HANDLE_ATTRIBUTE_VALUE_MODIFY_PRE';

    /**
     * @const string
     */
    const EVENT_MODEL_HANDLE_ATTRIBUTE_VALUE_MODIFY_POST = 'EVENT\MODEL\HANDLE_ATTRIBUTE_VALUE_MODIFY_POST';

    /**
     * @const string
     */
    const EVENT_MODEL_CREATE_PRE = 'EVENT\MODEL\CREATE_PRE';

    /**
     * @const string
     */
    const EVENT_MODEL_CREATE_POST = 'EVENT\MODEL\CREATE_POST';

    /**
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @var string
     */
    protected $databaseAddress = null;

    /**
     * @var string
     */
    protected $database = null;

    /**
     * @var string
     */
    protected $collection = 'bruno';

    /**
     * @var BrunoRepositoryInterface|null
     */
    private $repository = null;

    /**
     * @var array
     */
    private $dbAttributes = [];

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $definedAttributes = [];

    /**
     * @var bool
     */
    private $isNew = true;

    /**
     * @var array
     */
    private $fieldFilters = [];

    /**
     * @var bool
     */
    private $filtersEnabled = true;

    /**
     * Bruno constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @return BrunoInterface
     */
    public function save(): BrunoInterface
    {
        $query = $this->getRepository()->createNewQueryForModel($this);

        $adapters = $this->getDatabaseAdapters();

        if ($this->isNew() === true) {
            $this->getApplication()->triggerEvent(self::EVENT_MODEL_CREATE_PRE);
            $adapterActionParams = [
                'method' => 'insertOne',
                'params' => [
                    $query,
                    $this->getAttributes(),
                ],
            ];
        } else {
            $adapterActionParams = [
                'method' => 'updateOne',
                'params' => [
                    $query,
                    $this->getId(),
                    $this->getAttributes(),
                ],
            ];
        }

        foreach ($adapters as $adapter) {
            $response = call_user_func_array(
                [
                    $adapter,
                    $adapterActionParams['method'],
                ],
                $adapterActionParams['params']
            );

            if ($this->isNew() === true) {
                $this->attributes['_id'] = (string)$response;
            }
            $this->dbAttributes = $this->getAttributes();
        }

        if ($this->isNew() === true) {
            $this->getApplication()->triggerEvent(self::EVENT_MODEL_CREATE_POST, $this);
        }

        $this->setIsNew(false);

        return $this;
    }

    /**
     * @return BrunoRepositoryInterface|null
     */
    public function getRepository(): BrunoRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param BrunoRepositoryInterface $repository
     *
     * @return BrunoInterface
     */
    public function setRepository(BrunoRepositoryInterface $repository): BrunoInterface
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return DatabaseAdapterInterface[]
     * @todo this shouldnt be here
     */
    public function getDatabaseAdapters(): array
    {
        return $this->getApplication()
                    ->getRepositoryManager()
                    ->getModelAdapters($this->collection);
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $flag
     *
     * @return BrunoInterface
     */
    public function setIsNew(bool $flag = true): BrunoInterface
    {
        $this->isNew = $flag;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return BrunoInterface
     */
    public function setAttributes(array $attributes = []): BrunoInterface
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        if (isset($this->primaryKey) === true) {
            return $this->getAttribute($this->getPrimaryKey());
        }

        return null;
    }

    /**
     * @param string $attributeName
     *
     * @return mixed|null
     */
    public function getAttribute(string $attributeName)
    {
        return isset($this->getAttributes()[$attributeName]) === true ? $this->getAttributes()[$attributeName] : null;
    }

    /**
     * @return null|string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param string $primaryKey
     *
     * @return BrunoInterface
     */
    public function setPrimaryKey(string $primaryKey): BrunoInterface
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * @return BrunoInterface
     */
    public function delete(): BrunoInterface
    {
        $query = $this->getRepository()
                      ->createNewQueryForModel($this)
                      ->addAndCondition(
                          '_id',
                          '=',
                          $this->getId()
                      );

        $adapters = $this->getDatabaseAdapters();

        /**
         * @var DatabaseAdapterInterface $adapter
         */
        foreach ($adapters as $adapter) {
            $adapter->deleteOne($query);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $databaseName
     *
     * @return BrunoInterface
     */
    public function setDatabase(string $databaseName): BrunoInterface
    {
        $this->database = $databaseName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDatabaseAddress(): string
    {
        return $this->databaseAddress;
    }

    /**
     * @param string $databaseAddress
     *
     * @return BrunoInterface
     */
    public function setDatabaseAddress(string $databaseAddress): BrunoInterface
    {
        $this->databaseAddress = $databaseAddress;

        return $this;
    }

    /**
     * @param string $attribute
     * @param        $value
     *
     * @return BrunoInterface
     * @throws \InvalidArgumentException
     */
    public function setAttribute(string $attribute, $value): BrunoInterface
    {
        if (isset($this->getDefinedAttributes()[$attribute]) === false) {
            throw new \InvalidArgumentException("Property '$attribute' not defined.");
        }

        $this->getApplication()
             ->triggerEvent(
                 self::EVENT_MODEL_HANDLE_ATTRIBUTE_VALUE_MODIFY_PRE,
                 [
                     $attribute => $value,
                 ]
             );

        if ($attribute === 'password') {
            $this->addFieldFilter('password', new HashFilter());
        }

        if ($this->filtersEnabled === true
            && isset($this->fieldFilters[$attribute]) === true
        ) {
            foreach ($this->fieldFilters[$attribute] as $filter) {
                /* @var FieldModifierInterface $filter */
                $value = $filter->modify($value);
            }
        }

        $this->attributes[$attribute] = $value;

        $this->getApplication()
             ->triggerEvent(
                 self::EVENT_MODEL_HANDLE_ATTRIBUTE_VALUE_MODIFY_POST,
                 $this
             );

        return $this;
    }

    /**
     * @return bool
     */
    public function isDirty(): bool
    {
        return empty($this->getDirtyAttributes()) === false;
    }

    /**
     * @return array
     */
    public function getDirtyAttributes(): array
    {
        $attributes = $this->getAttributes();
        $databaseAttributes = $this->getDatabaseAttributes();

        $dirty = [];

        foreach ($attributes as $key => $value) {
            if (isset($databaseAttributes[$key]) === false) {
                $dirty[$key] = $value;
            } elseif ($value !== $databaseAttributes[$key]
                      && $this->originalIsNumericallyEquivalent($key) === false) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * @return array
     */
    public function getDatabaseAttributes(): array
    {
        return $this->dbAttributes;
    }

    /**
     * Determine if the new and old values for a given key are numerically equivalent.
     *
     * @param  string $key
     *
     * @return bool
     */
    private function originalIsNumericallyEquivalent($key): bool
    {
        $current = $this->getAttribute($key);
        $databaseAttributes = $this->getDatabaseAttributes();

        $original = $databaseAttributes[$key];

        return is_numeric($current)
               && is_numeric($original)
               && strcmp((string)$current, (string)$original) === 0;
    }

    /**
     * @param array $attributes
     *
     * @return BrunoInterface
     */
    public function setDatabaseAttributes(array $attributes = []): BrunoInterface
    {
        $this->dbAttributes = $attributes;

        return $this;
    }

    /**
     * @param string $attributeName
     *
     * @return mixed|null
     */
    public function getDatabaseAttribute(string $attributeName)
    {
        return isset($this->getDatabaseAttributes()[$attributeName]) === true ?
            $this->getDatabaseAttributes()[$attributeName] : null;
    }

    /**
     * @return array
     */
    public function getDefinedAttributes(): array
    {
        return $this->definedAttributes;
    }

    /**
     * @param array $definition
     *
     * @return BrunoInterface
     * @throws \InvalidArgumentException
     */
    public function defineModelAttributes(array $definition = []): BrunoInterface
    {
        $types = [
            'string',
            'password',
            'int',
            'integer',
            'float',
            'bool',
            'boolean',
            'array',
            'num',
            'numeric',
            'alpha_num',
            'alpha_numeric',
            'alpha_dash',
            'mixed',
        ];

        foreach ($definition as $key => $value) {
            if (is_array($value) === false ||
                is_string($key) === false
            ) {
                throw new \InvalidArgumentException('Attribute "' . $key . '" must be formatted as associative array');
            }
            if (isset($value['type']) === false) {
                throw new \InvalidArgumentException('Attribute "' . $key . '" must have "type" defined');
            }
            if (is_array($value['type']) === true ||
                in_array($value['type'], $types, true) === false
            ) {
                throw new \InvalidArgumentException('Unsupported model attribute type: ' . $value['type']);
            }

            $this->definedAttributes[$key] = $value['type'];
        }

        return $this;
    }

    /**
     * @param string                 $field
     * @param FieldModifierInterface $filter
     *
     * @return BrunoInterface
     */
    public function addFieldFilter(string $field, FieldModifierInterface $filter): BrunoInterface
    {
        if (isset($this->fieldFilters[$field]) === false) {
            $this->fieldFilters[$field] = [];
        }

        $this->fieldFilters[$field][] = $filter;

        return $this;
    }

    /**
     * @return array
     */
    public function getFieldFilters(): array
    {
        return $this->fieldFilters;
    }

    /**
     * @return BrunoInterface
     */
    public function enableFieldFilters(): BrunoInterface
    {
        if ($this->filtersEnabled === false) {
            $this->filtersEnabled = true;
        }

        return $this;
    }

    /**
     * @return BrunoInterface
     */
    public function disableFieldFilters(): BrunoInterface
    {
        if ($this->filtersEnabled === true) {
            $this->filtersEnabled = false;
        }

        return $this;
    }

    /**
     * Validates collection is set properly, throws exception otherwise
     *
     * @param string $resourceType
     *
     * @return BrunoInterface
     * @throws \Exception
     */
    public function confirmResourceOf(string $resourceType): BrunoInterface
    {
        if ($this->getCollection() !== $resourceType) {
            throw new \Exception('Model resource not configured correctly');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @param string $collection
     *
     * @return BrunoInterface
     */
    public function setCollection(string $collection): BrunoInterface
    {
        $this->collection = $collection;

        return $this;
    }
}
