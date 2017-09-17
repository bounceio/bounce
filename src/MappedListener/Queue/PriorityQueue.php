<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Ds\PriorityQueue as DsPriorityQueue;
use Ds\Set;
use Traversable;

/**
 * Class PriorityQueue
 * @package Bounce\Bounce\MappedListener\Queue
 */
class PriorityQueue implements QueueInterface
{
    /**
     * @var \Ds\PriorityQueue
     */
    private $prioritizedQueue;

    /**
     * PriorityQueue constructor.
     * @param iterable $mappedListeners
     */
    public function __construct(iterable $mappedListeners = [])
    {
        $this->prioritizedQueue = new DsPriorityQueue();

        $this->queueListeners($mappedListeners);
    }

    public function flush()
    {
        $this->prioritizedQueue->clear();
    }

    /**
     * @param MappedListenerInterface[] ...$mappedListeners
     * @return mixed
     */
    public function queue(MappedListenerInterface ...$mappedListeners)
    {
        return $this->queueListeners($mappedListeners);
    }

    /**
     * @param iterable $mappedListeners
     * @return $this
     */
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
        while (!$this->prioritizedQueue->isEmpty()) {
            yield $this->prioritizedQueue->pop()->listener();
        }
    }

    /**
     * @param \Bounce\Bounce\MappedListener\MappedListenerInterface $mappedListener
     */
    private function addListener(MappedListenerInterface $mappedListener)
    {
        $this->prioritizedQueue->push(
            $mappedListener,
            $mappedListener->priority()
        );
    }
}
