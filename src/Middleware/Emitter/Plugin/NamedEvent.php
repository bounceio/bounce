<?php

namespace Bounce\Bounce\Middleware\Emitter\Plugin;

use Bounce\Bounce\Event\Named;
use EventIO\InterOp\EventInterface;

/**
 * Class NamedEvent
 * @package Bounce\Bounce\Middleware\Emitter\Plugin
 */
class NamedEvent implements EmitterPluginInterface
{
    /**
     * @param $event
     * @param callable $next
     * @return mixed
     */
    public function __invoke($event, callable $next)
    {
        if (!$event instanceof EventInterface) {
            $event = Named::create($event);
        }

        return $next($event);
    }
}
