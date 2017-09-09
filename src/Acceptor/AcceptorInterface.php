<?php
namespace Bounce\Bounce\Acceptor;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use Traversable;

/**
 * Interface AcceptorInterface
 * @package Bounce\Bounce\Listener
 */
interface AcceptorInterface extends ListenerAcceptorInterface
{

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function listenersFor(EventInterface $event): Traversable;
}
