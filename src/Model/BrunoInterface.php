<?php

namespace Framework\Base\Model;

use Framework\Base\Application\ApplicationAwareInterface;
use Framework\Base\Model\Modifiers\FieldModifierInterface;
use Framework\Base\Repository\BrunoRepositoryInterface;

/**
 * Interface BrunoInterface
 * @package Framework\Base\Model
 */
interface BrunoInterface extends ApplicationAwareInterface
{
    /**
     * @return BrunoRepositoryInterface|null
     */
    public function getRepository();

    /**
     * @param \Framework\Base\Repository\BrunoRepositoryInterface $repository
     *
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function setRepository(BrunoRepositoryInterface $repository);

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getPrimaryKey();

    /**
     * @param string $primaryKey
     * @return BrunoInterface
     */
    public function setPrimaryKey(string $primaryKey);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     * @return BrunoInterface
     */
    public function setAttributes(array $attributes = []);

    /**
     * @param string $attributeName
     *
     * @return mixed|null
     */
    public function getAttribute(string $attributeName);

    /**
     * @param string $attribute
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute(string $attribute, $value);

    /**
     * @param array $attributes
     * @return BrunoInterface
     */
    public function setDatabaseAttributes(array $attributes = []);

    /**
     * @return array
     */
    public function getDatabaseAttributes();

    /**
     * @param string $attributeName
     * @return mixed|null
     */
    public function getDatabaseAttribute(string $attributeName);

    /**
     * @return string
     */
    public function getDatabaseAddress();

    /**
     * @param string $databaseAddress
     *
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function setDatabaseAddress(string $databaseAddress): BrunoInterface;

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * @param string $databaseName
     *
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function setDatabase(string $databaseName): BrunoInterface;

    /**
     * @return string
     */
    public function getCollection();

    /**
     * @param string $collectionName
     * @return BrunoInterface
     */
    public function setCollection(string $collectionName);

    /**
     * @return array
     */
    public function getDirtyAttributes();

    /**
     * @param bool $flag
     * @return BrunoInterface
     */
    public function setIsNew(bool $flag = true);

    /**
     * @return bool
     */
    public function isNew();

    /**
     * @return boolean
     */
    public function isDirty();

    /**
     * Set allowed attributes of model
     *
     * @param array $definition
     *
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function defineModelAttributes(array $definition = []): BrunoInterface;

    /**
     * @return array
     */
    public function getFieldFilters(): array;

    /**
     * @param string                 $field
     * @param FieldModifierInterface $filter
     *
     * @return \Framework\Base\Model\BrunoInterface
     */
    public function addFieldFilter(string $field, FieldModifierInterface $filter): BrunoInterface;

    /**
     * @return BrunoInterface
     */
    public function enableFieldFilters();

    /**
     * @return BrunoInterface
     */
    public function disableFieldFilters();

    /**
     * @param string $resourceType
     * @return BrunoInterface
     */
    public function confirmResourceOf(string $resourceType);

    /**
     * @return BrunoInterface
     */
    public function save();

    /**
     * @return BrunoInterface
     */
    public function delete();
}
