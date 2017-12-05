<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Database\DatabaseAdapterInterface;
use Framework\Base\Database\DatabaseQueryInterface;

class TestDatabaseAdapter implements DatabaseAdapterInterface
{
    /**
     * @var null
     */
    private $loadOneResult = null;

    /**
     * @param DatabaseQueryInterface $query
     * @param array                  $data
     *
     * @return string
     */
    public function insertOne(DatabaseQueryInterface $query, array $data = [])
    {
        return 'Not implemented';
    }

    /**
     * @param DatabaseQueryInterface $query
     *
     * @return null
     */
    public function loadOne(DatabaseQueryInterface $query)
    {
        return $this->loadOneResult;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setLoadOneResult($value)
    {
        $this->loadOneResult = $value;

        return $this;
    }

    /**
     * @param DatabaseQueryInterface $query
     *
     * @return null
     */
    public function loadMultiple(DatabaseQueryInterface $query)
    {
        return $this->loadOneResult;
    }

    /**
     * @param DatabaseQueryInterface $query
     * @param string                 $identifier
     * @param array                  $updateData
     *
     * @return string
     */
    public function updateOne(DatabaseQueryInterface $query, string $identifier, array $updateData = [])
    {
        return 'Not implemented';
    }

    /**
     * @param DatabaseQueryInterface $query
     *
     * @return string
     */
    public function deleteOne(DatabaseQueryInterface $query)
    {
        return 'Not implemented';
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return 'Not implemented';
    }

    /**
     * @param $client
     *
     * @return DatabaseAdapterInterface
     */
    public function setClient($client): DatabaseAdapterInterface
    {
        return $this;
    }

    /**
     * @return DatabaseQueryInterface
     */
    public function newQuery(): DatabaseQueryInterface
    {
        return new TestDatabaseQuery();
    }
}
