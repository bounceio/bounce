<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Traversable;

/**
 * Interface AcceptorMiddlewareInterface.
 */
interface DispatcherMiddlewareInterface
{
    /**
     * @param EventInterface $event
     * @param                $listener
     *
     * @return mixed
     */
    public function dispatch(EventInterface $event, $listener);
}