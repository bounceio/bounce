<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Generator;
use SplObjectStorage;
use SplPriorityQueue;
use SplQueue;
use Traversable;

/**
 * Class PriorityQueue
 * @package Bounce\Bounce\MappedListener\Queue
 */
class PriorityQueue implements QueueInterface
{
    /**
     * @var SplObjectStorage
     */
    private $mappedListeners;

    private $priorities;

    /**
     * PriorityQueue constructor.
     * @param array $mappedListeners
     */
    public function __construct(iterable $mappedListeners = [])
    {
        $this->priorities = [];
        $this->mappedListeners = new SplObjectStorage();

        foreach ($mappedListeners as $mappedListener) {
            $this->queue($mappedListener);
        }
    }

    /**
     * @param MappedListenerInterface[] ...$mappedListeners
     * @return mixed
     */
    public function queue(MappedListenerInterface ...$mappedListeners)
    {
        foreach ($mappedListeners as $mappedListener) {
            $this->mappedListeners->attach($mappedListener);
            $this->priorities[] = $mappedListener->priority();
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
            yield from $this->releaseQueuedListeners($prioritizedQueue);
        }
    }

    /**
     * @param SplPriorityQueue $prioritizedQueue
     *
     * @return Generator
     */
    private function releaseQueuedListeners(SplPriorityQueue $prioritizedQueue): Generator
    {
        $listeners = $prioritizedQueue->extract();

        while (!$listeners->isEmpty()) {
            yield $listeners->dequeue()->listener();
        }
    }

    /**
     * @return SplPriorityQueue
     */
    private function prioritizedQueue(): SplPriorityQueue
    {
        $prioritizedQueue = new SplPriorityQueue();
        $this->priorities = array_unique($this->priorities);

        foreach ($this->priorities as $priority) {
            $prioritizedQueue->insert(
                $this->enqueueMappedListeners($priority),
                $priority
            );
        }

        return $prioritizedQueue;
    }

    /**
     * @return SplQueue
     */
    private function createQueue(): SplQueue
    {
        $queue = new SplQueue();
        $queue->setIteratorMode(SplQueue::IT_MODE_FIFO | SplQueue::IT_MODE_DELETE);

        return $queue;
    }

    /**
     * @param $priority
     *
     * @return SplQueue
     */
    private function enqueueMappedListeners($priority): SplQueue
    {
        $queue = $this->createQueue();
        foreach ($this->mappedListeners as $mappedListener) {
            if ($mappedListener->priority() === $priority) {
                $queue->enqueue($mappedListener);
            }
        }

        return $queue;
    }
}
