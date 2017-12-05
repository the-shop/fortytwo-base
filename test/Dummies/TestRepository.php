<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Repository\BrunoRepository;

/**
 * Class TestRepository
 * @package Framework\Base\Test\Dummies
 */
class TestRepository extends BrunoRepository
{
    /**
     * @var string
     */
    protected $resourceName = 'tests';

    /**
     * @return array
     */
    public function getModelAttributesDefinition(): array
    {
        return $this->getRepositoryManager()
                    ->getRegisteredModelFields($this->resourceName);
    }

    /**
     * @param array $keyValues
     *
     * @return mixed
     */
    public function loadOneBy(array $keyValues = [])
    {
        return $this->getPrimaryAdapter()
                    ->loadOne(new TestDatabaseQuery());
    }

    /**
     * @param array $identifiers
     *
     * @return array
     */
    public function loadMultiple($identifiers = []): array
    {
        return $this->getPrimaryAdapter()
                    ->loadMultiple(new TestDatabaseQuery());
    }
}
