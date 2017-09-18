<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\Filter\EventListeners;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\MappedListener\Queue\QueueInterface;
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

    private $queue;

    private $filter;

    /**
     * @return MappedListeners
     */
    public static function create(QueueInterface $queue, $filter): self
    {
        return new self($queue, $filter);
    }

    /**
     * MappedListeners constructor.
     * @param iterable $mappedListeners
     */
    private function __construct(QueueInterface $queue, $filter)
    {
        $this->mappedListeners = new Set();
        $this->filter          = $filter;
        $this->queue   = $queue;
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
    private function queueFor(EventInterface $event): QueueInterface
    {
        $this->queue->flush();
        $this->queue->queueListeners(
          $this->queuedListenersFor($event)
        );

        return $this->queue;
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    private function queuedListenersFor(EventInterface $event)
    {
        yield from $this->mappedListeners->filter(
            $this->filter->filter($event)
        );
    }


}
