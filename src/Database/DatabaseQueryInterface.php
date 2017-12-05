<?php

namespace Framework\Base\Database;

/**
 * Interface DatabaseQueryInterface
 * @package Framework\Base\Database
 */
interface DatabaseQueryInterface
{
    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setDatabase(string $name): DatabaseQueryInterface;

    /**
     * @return string
     */
    public function getDatabase(): string;

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setCollection(string $name): DatabaseQueryInterface;

    /**
     * @return string
     */
    public function getCollection(): string;

    /**
     * @return array
     */
    public function build();

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function addSelectField(string $name): DatabaseQueryInterface;

    /**
     * @return array
     */
    public function getSelectFields();

    /**
     * @param int $limit
     *
     * @return DatabaseQueryInterface
     */
    public function setLimit(int $limit): DatabaseQueryInterface;

    /**
     * @return int
     */
    public function getLimit();

    /**
     * @param int $offset
     *
     * @return DatabaseQueryInterface
     */
    public function setOffset(int $offset): DatabaseQueryInterface;

    /**
     * @return int
     */
    public function getOffset();

    /**
     * @param string $field
     * @param string $condition
     * @param        $value
     *
     * @return DatabaseQueryInterface
     */
    public function addAndCondition(string $field, string $condition, $value): DatabaseQueryInterface;

    /**
     * @param string $field
     * @param array  $value
     *
     * @return DatabaseQueryInterface
     */
    public function whereInArrayCondition(string $field, $value = []): DatabaseQueryInterface;

    /**
     * @param string $identifier
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderBy(string $identifier): DatabaseQueryInterface;

    /**
     * @return string
     */
    public function getOrderBy();

    /**
     * @param string $orderDirection
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderDirection(string $orderDirection): DatabaseQueryInterface;

    /**
     * @return string
     */
    public function getOrderDirection();

    // TODO: implement
    // public function addOrCondition();
}
