<?php

namespace Framework\Base\Test\Event;

use Framework\Base\Test\Dummies\TestListener;
use Framework\Base\Test\UnitTest;

/**
 * Class EventsTest
 * @package Framework\Base\Test\Event
 */
class EventsTest extends UnitTest
{
    const TEST_EVENT = 'TEST_EVENT';

    /**
     * @var TestListener|string
     */
    private $listener = '';

    /**
     * EventsTest constructor.
     */
    public function __construct()
    {
        $this->listener = new TestListener();
        parent::__construct();
    }

    /**
     * Test application->listen() - adds new event and listener
     */
    public function testAddNewEventAndListener()
    {
        $app = $this->getApplication();
        $app->removeAllEventListeners();
        $app->listen(self::TEST_EVENT, TestListener::class);

        $this->assertEquals(
            [
                self::TEST_EVENT => [TestListener::class]
            ],
            $app->getEvents()
        );
    }

    /**
     * Test application->triggerEvent() - failed, RuntimeException
     */
    public function testApplicationTriggerEventFailed()
    {
        $app = $this->getApplication();
        $app->listen(self::TEST_EVENT, 'stdClass');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Listeners "stdClass" must implement ListenerInterface.');

        $app->triggerEvent(self::TEST_EVENT);
    }

    /**
     * Test application->triggerEvent() - with one listener - success
     */
    public function testApplicationTriggerEventWithOneListener()
    {
        $app = $this->getApplication();
        $app->listen(self::TEST_EVENT, TestListener::class);
        $out = $app->triggerEvent(self::TEST_EVENT, 'Test Payload');

        $this->assertEquals(['Test Payload'], $out);
    }

    /**
     * Test application->listen() - with same listener multiple times on one hook - only one registered
     */
    public function testApplicationRegisterOneListenerMultipleTimesOnSameHook()
    {
        $app = $this->getApplication();
        for ($i = 0; $i < 5; $i ++) {
            $app->listen(self::TEST_EVENT, TestListener::class);
        }

        $out = $app->triggerEvent(self::TEST_EVENT, 'Test Payload');

        $this->assertEquals(
            [
                'Test Payload'
            ],
            $out
        );
    }

    /**
     * Test application->triggerEvent() - Unregistered Event name - should return empty array -
     * success
     */
    public function testApplicationTriggerEventUnregisteredEventName()
    {
        $app = $this->getApplication();
        $out = $app->triggerEvent(self::TEST_EVENT, 'Test Payload');

        $this->assertEquals([], $out);
    }
}
