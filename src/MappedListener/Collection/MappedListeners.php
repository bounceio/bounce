<?php

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;

class MappedListeners implements MappedListenerCollectionInterface
{
    /**
     * @param MappedListenerInterface $mappedListener
     *
     * @return mixed
     */
    public function add(MappedListenerInterface $mappedListener)
    {
        // TODO: Implement add() method.
    }

    /**
     * @param EventInterface $event
     *
     * @return mixed
     */
    public function listenersFor(EventInterface $event)
    {
        // TODO: Implement listenersFor() method.
    }
}
