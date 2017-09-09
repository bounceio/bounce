<?php

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use SplObjectStorage;

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
     * @param MappedListenerInterface $mappedListener
     *
     * @return mixed
     */
    public function add(MappedListenerInterface $mappedListener)
    {
        $this->mappedListeners->attach($mappedListener);
    }

    /**
     * @param EventInterface $event
     *
     * @return mixed
     */
    public function listenersFor(EventInterface $event)
    {
        $listeners = [];
        foreach ($this->mappedListeners as $mappedListener) {
            if ($mappedListener->matches($event)) {
                $listeners[] = $mappedListener;
            }
        }

        usort($listeners, function (
            MappedListenerInterface $mappedListenerA,
            MappedListenerInterface $mappedListenerB
        ) {
            return $mappedListenerA->compare($mappedListenerB);
        });

        foreach ($listeners as $listener) {
            yield $listener->listener();
        }
    }
}
