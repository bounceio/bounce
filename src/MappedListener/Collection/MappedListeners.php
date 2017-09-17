<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\Filter\EventListeners;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\MappedListener\Queue\PriorityQueue;
use Ds\Map;
use EventIO\InterOp\EventInterface;
use Ds\Set;
use Symfony\Component\EventDispatcher\Event;
use Traversable;

/**
 * Class MappedListeners
 * @package Bounce\Bounce\MappedListener\Collection
 */
class MappedListeners implements MappedListenerCollectionInterface
{
    /**
     * @var \Ds\Set
     */
    private $mappedListeners;

    private $priorityQueue;

    private $queues;

    private $filter;

    /**
     * @param MappedListenerInterface[] ...$mappedListeners
     * @return MappedListeners
     */
    public static function create(MappedListenerInterface ...$mappedListeners): self
    {
        return new self($mappedListeners);
    }

    /**
     * MappedListeners constructor.
     * @param iterable $mappedListeners
     */
    private function __construct(iterable $mappedListeners = [])
    {
        $this->mappedListeners = new Set();
        $this->filter          = new EventListeners();
        $this->priorityQueue   = new PriorityQueue();
        $this->queues          = new Map();

        foreach ($mappedListeners as $mappedListener) {
            $this->add($mappedListener);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(MappedListenerInterface ...$mappedListeners)
    {
        return $this->addListeners($mappedListeners);
    }

    /**
     * @param iterable $mappedListeners
     *
     * @return $this
     */
    public function addListeners(iterable $mappedListeners)
    {
        foreach ($mappedListeners as $mappedListener) {
            $this->mappedListeners->add($mappedListener);
        }

        // reset the cache of previous queues;
        $this->queues->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listenersFor(EventInterface $event): Traversable
    {
        yield from $this->queueFor($event)->listeners();
    }

    /**
     * @param \EventIO\InterOp\EventInterface $event
     *
     * @return \Bounce\Bounce\MappedListener\Queue\PriorityQueue
     */
    private function queueFor(EventInterface $event): PriorityQueue
    {
        $this->priorityQueue->flush();
        $this->priorityQueue->queueListeners($this->queuedListenersFor($event));

        return $this->priorityQueue;
    }

    /**
     * @param EventInterface $event
     * @return Set
     */
    private function mappedListenersFor(EventInterface $event)
    {
        return $this->mappedListeners->filter(
            $this->filter->filter($event)
        );
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    private function queuedListenersFor(EventInterface $event)
    {
        if (!$this->queues->hasKey($event)) {
            $this->queues->put($event, $this->mappedListenersFor($event));
        }

        return $this->queues->get($event);
    }


}
