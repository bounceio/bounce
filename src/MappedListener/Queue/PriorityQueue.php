<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Ds\Map;
use Ds\PriorityQueue as DsPriorityQueue;
use Ds\Set;
use Generator;
use Traversable;

/**
 * Class PriorityQueue
 * @package Bounce\Bounce\MappedListener\Queue
 */
class PriorityQueue implements QueueInterface
{
    /**
     * @var \Ds\Map
     */
    private $mappedListeners;

    private $queueFilter;

    /**
     * PriorityQueue constructor.
     * @param iterable $mappedListeners
     */
    public function __construct(iterable $mappedListeners = [])
    {
        $this->mappedListeners = new Map();
        $this->queueFilter = new QueuePriorityFilter();
        $this->queueListeners($mappedListeners);
    }

    /**
     * @param MappedListenerInterface[] ...$mappedListeners
     * @return mixed
     */
    public function queue(MappedListenerInterface ...$mappedListeners)
    {
        return $this->queueListeners($mappedListeners);
    }

    public function queueListeners(iterable $mappedListeners)
    {
        $mappedListeners = new Set($mappedListeners);

        foreach ($mappedListeners as $mappedListener) {
            $this->addListener($mappedListener);
        }

        return $this;
    }

    /**
     * @return Traversable
     */
    public function listeners(): Traversable
    {
        $prioritizedQueue = $this->prioritizedQueue();

        while (!$prioritizedQueue->isEmpty()) {
            yield from $this->releaseQueuedListeners(
                $prioritizedQueue->pop()
            );
        }
    }

    /**
     * @param iterable $listeners
     *
     * @return \Generator
     */
    private function releaseQueuedListeners(iterable $listeners): Generator {
        foreach($listeners as $listener) {
            yield $listener->listener();
        }
    }

    /**
     * @return DsPriorityQueue
     */
    private function prioritizedQueue(): DsPriorityQueue
    {
        $prioritizedQueue = new DsPriorityQueue();
        $priorities       = new Set($this->mappedListeners->values());
        foreach ($priorities as $priority) {
            $prioritizedQueue->push(
                $this->enqueueMappedListeners($priority),
                $priority
            );
        }

        return $prioritizedQueue;
    }

    /**
     * @param $priority
     *
     * @return \Generator
     */
    private function enqueueMappedListeners($priority): Generator
    {
        $mappedListeners = $this->mappedListeners->filter(
            $this->queueFilter->filter($priority)
        );

        yield from $mappedListeners->keys();

    }

    /**
     * @param \Bounce\Bounce\MappedListener\MappedListenerInterface $mappedListener
     */
    private function addListener(MappedListenerInterface $mappedListener)
    {
        $this->mappedListeners->put(
            $mappedListener,
            $mappedListener->priority()
        );
    }
}
