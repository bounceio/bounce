<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Emitter;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Traversable;

/**
 * Interface AcceptorMiddlewareInterface.
 */
interface EmitterMiddlewareInterface
{
    /**
     * @param $event
     * @return EventInterface
     */
    public function queue($event): EventInterface;
}
