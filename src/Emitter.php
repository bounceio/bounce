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
class Emitter implements EmitterInterface, ListenerAcceptorInterface
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
     * @param array ...$events The event triggered
     *
     * @return mixed
     */
    public function emit(...$events)
    {
        $this->emitBatch($events);
    }

    /**
     * @param $events
     */
    public function emitBatch(iterable $events)
    {
        foreach ($events as $event) {
            $this->queueEvent($event);
        }
    }

    /**
     * @param EventInterface $event The event triggered
     *
     * @return mixed
     */
    public function emitEvent(EventInterface $event)
    {
        $this->queueEvent($event);
    }

    /**
     * @param string $event The event name to emit
     *
     * @return mixed
     */
    public function emitName($event)
    {
        return $this->queueEvent($event);
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
    }

    private function queueEvent($event)
    {
        $event = $this->middleware->queue($event);
        foreach ($this->acceptor->listenersFor($event) as $listener) {
            $listener->handle($event);
        }
    }
}
