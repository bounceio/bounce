<?php

namespace Bounce\Bounce;

use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;

/**
 * Class Emitter
 * @package Bounce\Bounce
 */
class Emitter implements EmitterInterface, ListenerAcceptorInterface
{
    /**
     * @param array ...$events The event triggered
     * @return mixed
     */
    public function emit(...$events)
    {
        // TODO: Implement emit() method.
    }

    /**
     * @param EventInterface $event The event triggered
     * @return mixed
     */
    public function emitEvent(EventInterface $event)
    {
        // TODO: Implement emitEvent() method.
    }

    /**
     * @param string $event The event name to emit
     * @return mixed
     */
    public function emitName($event)
    {
        // TODO: Implement emitName() method.
    }

    /**
     * @param string $eventName The name of the event to listen for
     * @param callable|ListenerInterface $listener A listener or callable
     * @param int $priority Used to prioritise listeners for the same event
     * @return mixed
     */
    public function addListener(
        $eventName,
        $listener,
        $priority = self::PRIORITY_NORMAL
    )
    {
        // TODO: Implement addListener() method.
    }
}
