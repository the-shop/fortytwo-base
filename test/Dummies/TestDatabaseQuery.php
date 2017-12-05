<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Database\DatabaseQueryInterface;

/**
 * Class TestDatabaseQuery
 * @package Framework\Base\Test\Dummies
 */
class TestDatabaseQuery implements DatabaseQueryInterface
{
    /**
     * @var string
     */
    private $database = '';
    /**
     * @var string
     */
    private $collection = '';
//    private $offset = '';
//    private $limit = '';
//    private $selectFields = [];
//    private $conditions = [];

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setDatabase(string $name): DatabaseQueryInterface
    {
        $this->database = $name;

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
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setCollection(string $name): DatabaseQueryInterface
    {
        $this->collection = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function build()
    {
        return $this;
    }

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function addSelectField(string $name): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSelectFields()
    {
        return 'Not implemented';
    }

    /**
     * @param int $limit
     *
     * @return DatabaseQueryInterface
     */
    public function setLimit(int $limit): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return 'Not implemented';
    }

    /**
     * @param int $offset
     *
     * @return DatabaseQueryInterface
     */
    public function setOffset(int $offset): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return 'Not implemented';
    }

    /**
     * @param string $field
     * @param string $condition
     * @param        $value
     *
     * @return DatabaseQueryInterface
     */
    public function addAndCondition(string $field, string $condition, $value): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @param string $field
     * @param array  $value
     *
     * @return DatabaseQueryInterface
     */
    public function whereInArrayCondition(string $field, $value = []): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @param string $identifier
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderBy(string $identifier): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return 'Not implemented';
    }

    /**
     * @param string $orderDirection
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderDirection(string $orderDirection): DatabaseQueryInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDirection()
    {
        return 'Not implemented';
    }
}
