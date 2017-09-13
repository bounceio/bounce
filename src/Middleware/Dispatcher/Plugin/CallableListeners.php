<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher\Plugin;

use Bounce\Bounce\Event\Named;
use Bounce\Bounce\Listener\CallableListener;
use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use Generator;

/**
 * Class NamedEvent
 * @package Bounce\Bounce\Middleware\Emitter\Plugin
 */
class CallableListeners
{
    /**
     * @var MappedListenerCollectionInterface
     */
    private $listenerMapCollection;

    /**
     * CallableListeners constructor.
     *
     * @param MappedListenerCollectionInterface $listenerMapCollection
     */
    public function __construct(MappedListenerCollectionInterface $listenerMapCollection)
    {
        $this->listenerMapCollection = $listenerMapCollection;
    }


    /**
     * @param          $eventDispatchLoop
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(
        $eventDispatchLoop,
        callable $next
    ) {
        $eventDispatchLoop->listeners = $this->callables($eventDispatchLoop->listeners);
        return $next($eventDispatchLoop);
    }

    /**
     * @param iterable $listeners
     *
     * @return Generator
     */
    public function callables(iterable $listeners): Generator
    {
        foreach ($listeners as $listener) {
            yield $this->callableFromListener($listener);
        }
    }

    /**
     * @param $listener
     *
     * @return ListenerInterface
     */
    private function callableFromListener($listener): ListenerInterface
    {
        if ( (!$listener instanceof ListenerInterface) && (is_callable($listener))) {
            echo "Upgrading to callablelistener\n";
            $callableListener = new CallableListener($listener);
            $this->listenerMapCollection->replaceListener($listener, $callableListener);
            $listener = $callableListener;
        }

        return $listener;
    }

}
