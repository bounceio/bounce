<?php

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\MappedListener\Queue\PriorityQueue;
use EventIO\InterOp\EventInterface;
use SplObjectStorage;
use Traversable;

/**
 * Class MappedListeners
 * @package Bounce\Bounce\MappedListener\Collection
 */
class MappedListeners implements MappedListenerCollectionInterface
{
    /**
     * @var SplObjectStorage
     */
    private $mappedListeners;

    public static function create(SplObjectStorage $mappedListeners = null): self
    {
        if (null === $mappedListeners) {
            $mappedListeners = new SplObjectStorage();
        }

        return new self($mappedListeners);
    }

    /**
     * MappedListeners constructor.
     *
     * @param SplObjectStorage $mappedListeners
     */
    private function __construct(SplObjectStorage $mappedListeners)
    {
        $this->mappedListeners = $mappedListeners;
    }

    /**
     * {@inheritdoc}
     */
    public function add(MappedListenerInterface $mappedListener)
    {
        $this->mappedListeners->attach($mappedListener);
    }

    /**
     * {@inheritdoc}
     */
    public function listenersFor(EventInterface $event): Traversable
    {
        $queue = new PriorityQueue();
        foreach ($this->mappedListeners as $mappedListener) {
            if ($mappedListener->matches($event)) {
                $queue->queue($mappedListener);
            }
        }

        yield from $queue->listeners();
    }
}
