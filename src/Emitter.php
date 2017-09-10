<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\Middleware\Emitter\EmitterMiddlewareInterface;
use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;

/**
 * Class Emitter.
 */
final class Emitter implements EmitterInterface, ListenerAcceptorInterface
{
    /**
     * @var AcceptorInterface
     */
    private $acceptor;


    private $middleware;

    /**
     * Emitter constructor.
     *
     * @param AcceptorInterface $acceptor
     * @param EmitterMiddlewareInterface $middleware
     */
    public function __construct(
        AcceptorInterface $acceptor,
        EmitterMiddlewareInterface $middleware
    ) {
        $this->acceptor     = $acceptor;
        $this->middleware   = $middleware;
    }

    /**
     * @param string                     $eventName The name of the event to listen for
     * @param callable|ListenerInterface $listener  A listener or callable
     * @param int                        $priority  Used to prioritise listeners for the same event
     *
     * @return mixed
     */
    public function addListener(
        $eventName,
        $listener,
        $priority = self::PRIORITY_NORMAL
    ) {
        $this->acceptor->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * @param array ...$events
     *
     * @return Emitter
     */
    public function emit(...$events): self
    {
        $this->emitBatch($events);

        return $this;
    }

    /**
     * @param iterable $events
     *
     * @return Emitter
     */
    public function emitBatch(iterable $events): self
    {
        foreach ($events as $event) {
            $this->queueEvent($event);
        }

        return $this;
    }

    /**
     * @param EventInterface $event
     *
     * @return $this
     */
    public function emitEvent(EventInterface $event)
    {
        $this->queueEvent($event);

        return $this;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function emitName($event)
    {
        $this->queueEvent($event);

        return $this;
    }

    /**
     * @param $event
     */
    private function queueEvent($event)
    {
        $event = $this->middleware->queue($event);
        foreach ($this->acceptor->listenersFor($event) as $listener) {
            $listener->handle($event);
        }
    }
}
