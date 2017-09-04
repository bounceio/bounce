<?php
namespace Bounce\Bounce\Listener;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;

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
    public function listenersFor(EventInterface $event);
}
