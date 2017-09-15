<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\MappedListener\Queue\PriorityQueue;
use EventIO\InterOp\EventInterface;
use Ds\Set;
use Traversable;

/**
 * Class MappedListeners
 * @package Bounce\Bounce\MappedListener\Collection
 */
class MappedListeners implements MappedListenerCollectionInterface
{
    /**
     * @var Set
     */
    private $mappedListeners;

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
        $mappedListeners       = new Set($mappedListeners);
        $this->mappedListeners = $this->mappedListeners->union($mappedListeners);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listenersFor(EventInterface $event): Traversable
    {
        $queue = $this->queueFor($event);

        yield from $queue->listeners();
    }

    /**
     * @param \EventIO\InterOp\EventInterface $event
     *
     * @return \Bounce\Bounce\MappedListener\Queue\PriorityQueue
     */
    private function queueFor(EventInterface $event): PriorityQueue
    {
        $queue = new PriorityQueue();

        $filter = function(MappedListenerInterface $mappedListener) use ($event) {
            return $mappedListener->matches($event);
        };

        $mappedListeners = $this->mappedListeners->filter($filter);

        return $queue->queueListeners($mappedListeners);
    }
}
