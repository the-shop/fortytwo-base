<?php

namespace Framework\Base\Repository;

/**
 * Class GenericRepository
 * @package Framework\Base\Repository
 */
class GenericRepository extends BrunoRepository
{
    /**
     * @return array
     */
    public function getModelAttributesDefinition(): array
    {
        return $this->getRepositoryManager()
                    ->getRegisteredModelFields($this->getCollection());
    }
}
