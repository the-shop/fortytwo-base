<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Render\Render;
use Framework\Base\Response\ResponseInterface;

/**
 * Class Memory
 * @package Framework\Base\Test
 */
class MemoryRenderer extends Render
{
    private $response = null;

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function render(ResponseInterface $response)
    {
        $this->response = $response;

        return $this->response;
    }
}
