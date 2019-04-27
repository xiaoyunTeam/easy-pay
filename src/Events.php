<?php

namespace XiaoYun\Pay;

use Exception;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author xiaoyun <shustudio@yeah.net>
 *
 * @method static Event dispatch($eventName, Event $event = null) Dispatches an event to all registered listeners
 * @method static array getListeners($eventName = null) Gets the listeners of a specific event or all listeners sorted by descending priority.
 * @method static int|null getListenerPriority($eventName, $listener) Gets the listener priority for a specific event.
 * @method static bool hasListeners($eventName = null) Checks whether an event has any registered listeners.
 * @method static addListener($eventName, $listener, $priority = 0) Adds an event listener that listens on the specified events.
 * @method static removeListener($eventName, $listener) Removes an event listener from the specified events.
 * @method static addSubscriber(EventSubscriberInterface $subscriber) Adds an event subscriber.
 * @method static removeSubscriber(EventSubscriberInterface $subscriber)
 */
class Events
{
    /**
     * Start pay.
     *
     * @Event("XiaoYun\Pay\Events\PayStarting")
     */
    const PAY_STARTING = 'yansongda.pay.starting';

    /**
     * Pay started.
     *
     * @Event("XiaoYun\Pay\Events\PayStarted")
     */
    const PAY_STARTED = 'yansongda.pay.started';

    /**
     * Api requesting.
     *
     * @Event("XiaoYun\Pay\Events\ApiRequesting")
     */
    const API_REQUESTING = 'yansongda.pay.api.requesting';

    /**
     * Api requested.
     *
     * @Event("XiaoYun\Pay\Events\ApiRequested")
     */
    const API_REQUESTED = 'yansongda.pay.api.requested';

    /**
     * Sign error.
     *
     * @Event("XiaoYun\Pay\Events\SignFailed")
     */
    const SIGN_FAILED = 'yansongda.pay.sign.failed';

    /**
     * Receive request.
     *
     * @Event("XiaoYun\Pay\Events\RequestReceived")
     */
    const REQUEST_RECEIVED = 'yansongda.pay.request.received';

    /**
     * Method called.
     *
     * @Event("XiaoYun\Pay\Events\MethodCalled")
     */
    const METHOD_CALLED = 'yansongda.pay.method.called';

    /**
     * dispatcher.
     *
     * @var EventDispatcher
     */
    protected static $dispatcher;

    /**
     * Forward call.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string $method
     * @param array  $args
     *
     * @throws Exception
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * Forward call.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param string $method
     * @param array  $args
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * setDispatcher.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @param EventDispatcher $dispatcher
     *
     * @return void
     */
    public static function setDispatcher(EventDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }

    /**
     * getDispatcher.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @return EventDispatcher
     */
    public static function getDispatcher(): EventDispatcher
    {
        if (self::$dispatcher) {
            return self::$dispatcher;
        }

        return self::$dispatcher = self::createDispatcher();
    }

    /**
     * createDispatcher.
     *
     * @author xiaoyun <shustudio@yeah.net>
     *
     * @return EventDispatcher
     */
    public static function createDispatcher(): EventDispatcher
    {
        return new EventDispatcher();
    }
}
