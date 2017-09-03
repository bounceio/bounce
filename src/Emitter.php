<?php

namespace Bounce\Bounce;

use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;

/**
 * Class Emitter
 * @package Bounce\Bounce
 */
class Emitter implements EmitterInterface
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
}
