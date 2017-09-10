<?php

namespace Bounce\Bounce\Dispatcher;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\EventQueue\EventQueue;
use Bounce\Bounce\EventQueue\EventQueueInterface;
use EventIO\InterOp\EventInterface;

/**
 * Class Dispatcher
 * @package Bounce\Bounce\Dispatcher
 */
final class Dispatcher implements DispatcherInterface
{
    /**
     * @var EventQueueInterface
     */
    private $queue;

    /**
     * @var bool
     */
    private $dispatching = false;

    /**
     * @param EventQueueInterface|null $queue
     * @return Dispatcher
     */
    public static function create(
        EventQueueInterface $queue = null
    ): self {

        if (!$queue) {
            $queue = EventQueue::create();
        }

        return new self($queue);
    }

    /**
     * Dispatcher constructor.
     * @param EventQueueInterface $queue
     */
    private function __construct(EventQueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param EventInterface[] ...$events
     * @return DispatcherInterface
     */
    public function enqueue(EventInterface ...$events): DispatcherInterface
    {
        $this->queue->queueEvents($events);

        return $this;
    }

    /**
     * @return bool
     */
    public function isDispatching(): bool
    {
        return $this->dispatching;
    }

    /**
     * @param AcceptorInterface $acceptor
     * @return DispatcherInterface
     */
    public function dispatch(AcceptorInterface $acceptor): DispatcherInterface
    {
        $this->dispatching = true;

        foreach ($this->queue->events() as $event) {
            $this->dispatchEvent($event, $acceptor);
        }
        $this->dispatching = false;

        return $this;
    }

    /**
     * @param EventInterface $event The event to dispatch through listeners
     * @param AcceptorInterface $acceptor
     */
    private function dispatchEvent(
        EventInterface $event,
        AcceptorInterface $acceptor
    ) {
        if (!$event->isPropagationStopped()) {
            foreach ($acceptor->listenersFor($event) as $listener) {
                if ($event->isPropagationStopped()) {
                    return;
                }
                $listener->handle($event);
            }
        }
    }
}
