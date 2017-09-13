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

    /**
     * PriorityQueue constructor.
     * @param array $mappedListeners
     */
    public function __construct($mappedListeners = [])
    {
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
        }

        return $this;
    }

    /**
     * @return Traversable
     */
    public function listeners(): Traversable
    {
        $prioritizedQueue = $this->prioritizedQueue();
        $prioritizedQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

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
        $prioritizedListeners = $prioritizedQueue->extract();
        $priority             = $prioritizedListeners['priority'];
        $listeners            = $prioritizedListeners['data'];

        while (!$listeners->isEmpty()) {
            yield $priority => $listeners->dequeue()->listener();
        }
    }

    /**
     * @return SplPriorityQueue
     */
    private function prioritizedQueue(): SplPriorityQueue
    {
        $prioritizedQueue = new SplPriorityQueue();

        $priorities = [];
        foreach ($this->mappedListeners as $mappedListener) {
            $priorities[] = $mappedListener->priority();
        }

        foreach (array_unique($priorities) as $priority) {
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
