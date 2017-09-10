<?php
namespace Bounce\Bounce\Middleware\Acceptor;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Traversable;

class AcceptorMiddleware implements AcceptorMiddlewareInterface
{

    /**
     * @param $map
     * @param $listener
     * @param $priority
     *
     * @return MappedListenerInterface
     */
    public function listenerAdd($map, $listener, $priority): MappedListenerInterface
    {
        // TODO: Implement listenerAdd() method.
    }

    /**
     * @param EventInterface $event
     * @param $listeners
     *
     * @return Traversable
     */
    public function listenersFor(EventInterface $event, $listeners): Traversable
    {
        // TODO: Implement listenersFor() method.
    }
}