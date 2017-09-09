<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Acceptor;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use Traversable;

/**
 * Interface AcceptorInterface.
 */
interface AcceptorInterface extends ListenerAcceptorInterface
{
    /**
     * @param EventInterface $event
     *
     * @return mixed
     */
    public function listenersFor(EventInterface $event): Traversable;
}
