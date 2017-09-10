<?php

namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
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
     * @return Traversable
     */
    public function listeners(): Traversable
    {
        $prioritizedQueue = $this->prioritizedQueue();
        while (!$prioritizedQueue->isEmpty()) {
            $listeners = $prioritizedQueue->extract();
            while (!$listeners->isEmpty()) {
                yield $listeners->dequeue()->listener();
            }
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
            $queue = $this->createQueue();
            foreach ($this->mappedListeners as $mappedListener) {
                if ($mappedListener->priority() === $priority) {
                    $queue->enqueue($mappedListener);
                }
            }
            $prioritizedQueue->insert($queue, $priority);
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
}
