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
    public function getRepository(): BrunoRepositoryInterface;

    /**
     * @param BrunoRepositoryInterface $repository
     *
     * @return BrunoInterface
     */
    public function setRepository(BrunoRepositoryInterface $repository): BrunoInterface;

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
     *
     * @return BrunoInterface
     */
    public function setPrimaryKey(string $primaryKey): BrunoInterface;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @param array $attributes
     *
     * @return BrunoInterface
     */
    public function setAttributes(array $attributes = []): BrunoInterface;

    /**
     * @param string $attributeName
     *
     * @return mixed|null
     */
    public function getAttribute(string $attributeName);

    /**
     * @param string $attribute
     * @param        $value
     *
     * @return BrunoInterface
     */
    public function setAttribute(string $attribute, $value): BrunoInterface;

    /**
     * @param array $attributes
     *
     * @return BrunoInterface
     */
    public function setDatabaseAttributes(array $attributes = []): BrunoInterface;

    /**
     * @return array
     */
    public function getDatabaseAttributes(): array;

    /**
     * @param string $attributeName
     *
     * @return mixed|null
     */
    public function getDatabaseAttribute(string $attributeName);

    /**
     * @return string
     */
    public function getDatabaseAddress(): string;

    /**
     * @param string $databaseAddress
     *
     * @return BrunoInterface
     */
    public function setDatabaseAddress(string $databaseAddress): BrunoInterface;

    /**
     * @return string
     */
    public function getDatabase(): string;

    /**
     * @param string $databaseName
     *
     * @return BrunoInterface
     */
    public function setDatabase(string $databaseName): BrunoInterface;

    /**
     * @return string
     */
    public function getCollection(): string;

    /**
     * @param string $collectionName
     *
     * @return BrunoInterface
     */
    public function setCollection(string $collectionName): BrunoInterface;

    /**
     * @return array
     */
    public function getDirtyAttributes(): array;

    /**
     * @param bool $flag
     *
     * @return BrunoInterface
     */
    public function setIsNew(bool $flag = true): BrunoInterface;

    /**
     * @return bool
     */
    public function isNew(): bool;

    /**
     * @return bool
     */
    public function isDirty(): bool;

    /**
     * Set allowed attributes of model
     *
     * @param array $definition
     *
     * @return BrunoInterface
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
     * @return BrunoInterface
     */
    public function addFieldFilter(string $field, FieldModifierInterface $filter): BrunoInterface;

    /**
     * @return BrunoInterface
     */
    public function enableFieldFilters(): BrunoInterface;

    /**
     * @return BrunoInterface
     */
    public function disableFieldFilters(): BrunoInterface;

    /**
     * @param string $resourceType
     *
     * @return BrunoInterface
     */
    public function confirmResourceOf(string $resourceType): BrunoInterface;

    /**
     * @return BrunoInterface
     */
    public function save(): BrunoInterface;

    /**
     * @return BrunoInterface
     */
    public function delete(): BrunoInterface;
}
