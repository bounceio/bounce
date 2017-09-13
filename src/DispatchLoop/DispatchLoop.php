<?php
namespace Bounce\Bounce\DispatchLoop;

use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Generator;

/**
 * Class DispatchLoop
 * @package Bounce\Bounce\DispatchLoop
 */
class DispatchLoop
{
    /**
     * @var EventInterface
     */
    private $event;

    /**
     * @var iterable
     */
    private $listeners;

    /**
     * @var boolean
     */
    private $dispatching;

    /**
     * @param $dto
     *
     * @return DispatchLoop
     */
    public static function fromDto($dto): self
    {
        return new self($dto->event, $dto->listeners);
    }

    /**
     * DispatchLoop constructor.
     *
     * @param EventInterface $event
     * @param iterable       $listeners
     */
    public function __construct(EventInterface $event, iterable $listeners)
    {
        $this->event     = $event;
        $this->listeners = $listeners;
    }

    /**
     * @return bool
     */
    public function isDispatching()
    {
        return $this->dispatching;
    }

    /**
     * @return $this
     */
    public function dispatch()
    {
        $this->dispatching = true;
        $this->dispatchEventToListeners();
        $this->dispatching = false;

        return $this;
    }

    /**
     * Dispatch an event to the listeners
     */
    private function dispatchEventToListeners()
    {
        $listeners          = $this->listeners();
        $loadedListeners    = [];

        while ((!$this->event->isPropagationStopped()) && $listeners->valid()) {
            $listener = $listeners->current();
            $loadedListeners[] = $listener;
            $this->dispatchListener($listener);
            $listeners->next();
        }

        $this->listeners = $loadedListeners;
    }

    /**
     * @return Generator
     */
    private function listeners(): Generator
    {
        yield from $this->listeners;
    }

    /**
     * @param ListenerInterface $listener
     *
     * @return mixed
     */
    private function dispatchListener(ListenerInterface $listener)
    {
        $listener->handle($this->event);
    }

}
