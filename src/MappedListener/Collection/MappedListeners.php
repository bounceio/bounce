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
        $this->mappedListeners = new SplObjectStorage();

        foreach ($mappedListeners as $mappedListener) {
            $this->add($mappedListener);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(MappedListenerInterface ...$mappedListeners)
    {
        foreach ($mappedListeners as $mappedListener) {
            $this->mappedListeners->attach($mappedListener);
        }

        return $this;
    }

    /**
     * @param $originalListener
     * @param $newListener
     *
     * @return $this
     */
    public function replaceListener(
        $originalListener,
        $newListener
    ): MappedListenerCollectionInterface {
        foreach ($this->mappedListeners as $mappedListener) {
            if ($mappedListener->listener() === $originalListener) {
                $this->replace(
                    $mappedListener,
                    $mappedListener->withListener($newListener)
                );
            }
        }

        return $this;
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

    private function replace(
        MappedListenerInterface $old,
        MappedListenerInterface $new
    ) {
        $this->mappedListeners->offsetUnset($old);
        $this->mappedListeners->offsetSet($new);
    }
}
