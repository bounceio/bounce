<?php
namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;

/**
 * Interface MappedListenerCollectionInterface
 * @package Bounce\Bounce\MappedListener\Collection
 */
interface MappedListenerCollectionInterface
{

    /**
     * @param MappedListenerInterface $mappedListener
     * @return mixed
     */
    public function add(MappedListenerInterface $mappedListener);

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function listenersFor(EventInterface $event);
}
