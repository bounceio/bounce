<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Acceptor;

use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddlewareInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Traversable;

class Acceptor implements AcceptorInterface
{
    /**
     * @var AcceptorMiddlewareInterface
     */
    private $middleware;

    /**
     * @var MappedListenerCollectionInterface
     */
    private $mappedListeners;

    /**
     * Acceptor constructor.
     *
     * @param AcceptorMiddlewareInterface       $middleware
     * @param MappedListenerCollectionInterface $mappedListeners
     */
    public function __construct(
        AcceptorMiddlewareInterface $middleware,
        MappedListenerCollectionInterface $mappedListeners
    ) {
        $this->middleware = $middleware;
        $this->mappedListeners = $mappedListeners;
    }

    /**
     * @param EventInterface $event
     *
     * @return Traversable
     */
    public function listenersFor(EventInterface $event): Traversable
    {
        yield from $this->middleware->listenersFor($event, $this->mappedListeners);
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
        $mappedListener = $this->middleware->listenerAdd($eventName, $listener, $priority);

        $this->mappedListeners->add($mappedListener);

        return $this;
    }
}
