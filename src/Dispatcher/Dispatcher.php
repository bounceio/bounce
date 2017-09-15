<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Dispatcher;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\DispatchLoop\DispatchLoop;
use Bounce\Bounce\EventQueue\EventQueue;
use Bounce\Bounce\EventQueue\EventQueueInterface;
use Bounce\Bounce\Middleware\Dispatcher\DispatcherMiddlewareInterface;
use EventIO\InterOp\EventInterface;
use stdClass;

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
     * @var DispatcherMiddlewareInterface
     */
    private $middleware;

    /**
     * @var DispatchLoop
     */
    private $currentLoop;

    /**
     * @param DispatcherMiddlewareInterface $dispatcherMiddleware
     * @param EventQueueInterface|null      $queue
     *
     * @return Dispatcher
     */
    public static function create(
        DispatcherMiddlewareInterface $dispatcherMiddleware,
        EventQueueInterface $queue = null
    ): self {
        if (!$queue) {
            $queue = EventQueue::create();
        }

        return new self($queue, $dispatcherMiddleware);
    }

    /**
     * Dispatcher constructor.
     *
     * @param EventQueueInterface           $queue
     * @param DispatcherMiddlewareInterface $dispatcherMiddleware
     */
    private function __construct(
        EventQueueInterface $queue,
        DispatcherMiddlewareInterface $dispatcherMiddleware
    ) {
        $this->queue        = $queue;
        $this->middleware   = $dispatcherMiddleware;
    }

    /**
     * @param EventInterface[] ...$events
     * @return DispatcherInterface
     */
    public function enqueue(...$events): DispatcherInterface
    {
        $this->queue->queueEvents($events);

        return $this;
    }

    /**
     * @return bool
     */
    public function isDispatching(): bool
    {
        if ($this->currentLoop) {
            return $this->currentLoop->isDispatching();
        }

        return false;
    }

    /**
     * @param AcceptorInterface $acceptor
     * @param iterable          $events
     *
     * @return DispatcherInterface
     */
    public function dispatch(
        AcceptorInterface $acceptor,
        iterable $events = []
    ): DispatcherInterface  {

        $this->enqueue($events);

        foreach ($this->queue->events() as $event) {
            $this->dispatchEvent($event, $acceptor);
        }

        return $this;
    }

    /**
     * @param mixed $event The event to dispatch through listeners
     * @param AcceptorInterface $acceptor
     */
    private function dispatchEvent(
        $event,
        AcceptorInterface $acceptor
    ) {
        $this->currentLoop = $this->createDispatchLoop(
            $event,
            $acceptor
        );
        $this->currentLoop->dispatch();
    }

    /**
     * @param $event
     * @param $acceptor
     *
     * @return mixed
     */
    private function createDispatchLoop($event, $acceptor): DispatchLoop
    {
        $dispatchLoop               = new stdClass();
        $dispatchLoop->event        = $event;
        $dispatchLoop->listeners    = $acceptor->listenersFor($event);

        return DispatchLoop::fromDto($this->middleware->dispatch($dispatchLoop));
    }
}
