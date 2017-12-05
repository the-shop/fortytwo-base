<?php

namespace Framework\Base\Test\Logger;

use Framework\Base\Logger\Log;
use Framework\Base\Test\UnitTest;

/**
 * Class LogTest
 * @package Framework\Base\Test\Logger
 */
class LogTest extends UnitTest
{
    /**
     * @var
     */
    private $payload;
    /** @var  Log */
    private $log;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->payload = 'test';
        $this->log = new Log($this->payload);
    }

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     */
    public function testIsInstantiable()
    {
        $this->assertInstanceOf(Log::class, $this->log);

        $this->assertEquals($this->payload, $this->log->getPayload());
    }

    /**
     *
     */
    public function testSetData()
    {
        $this->log->setData('testKey', 'testValue');

        $this->assertAttributeContains('testValue', 'data', $this->log);

        $this->assertArrayHasKey('testKey', $this->log->getAllData());

        $this->assertEquals('testValue', $this->log->getData('testKey'));
    }

    /**
     *
     */
    public function testIsException()
    {
        $this->assertFalse($this->log->isException());

        $payload = new \Exception($this->payload);
        $this->log = new Log($payload);

        $this->assertTrue($this->log->isException());
    }
}
