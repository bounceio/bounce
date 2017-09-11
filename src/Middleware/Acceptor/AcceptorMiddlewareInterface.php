<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Traversable;

/**
 * Interface AcceptorMiddlewareInterface.
 */
interface AcceptorMiddlewareInterface
{
    /**
     * @param $map
     * @param $listener
     * @param $priority
     *
     * @return MappedListenerInterface
     */
    public function listenerAdd($map, $listener, $priority): MappedListenerInterface;
}
