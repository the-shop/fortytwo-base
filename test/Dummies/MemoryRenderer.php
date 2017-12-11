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
    /**
     * @var null
     */
    private $response = null;

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response)
    {
        $this->response = $response;

        return $this->response;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
