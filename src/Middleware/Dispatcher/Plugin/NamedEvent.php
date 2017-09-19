<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher\Plugin;

use Bounce\Emitter\Event\Named;
use EventIO\InterOp\EventInterface;

/**
 * Class NamedEvent
 * @package Bounce\Bounce\Middleware\Emitter\Plugin
 */
class NamedEvent
{
    /**
     * @param          $eventDispatchLoop
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(
        $eventDispatchLoop,
        callable $next
    ) {
        if (!$eventDispatchLoop->event instanceof EventInterface) {
            $eventDispatchLoop->event = Named::create($eventDispatchLoop->event);
        }

        return $next($eventDispatchLoop);
    }
}
