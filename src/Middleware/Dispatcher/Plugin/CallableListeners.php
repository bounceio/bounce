<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher\Plugin;

use Bounce\Bounce\Listener\CallableListener;
use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use Ds\Map;
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
    private $listenerMap;

    /**
     * CallableListeners constructor.
     */
    public function __construct()
    {
        $this->listenerMap = new Map();
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
            if (is_callable($listener)) {
                $listener = $this->callableFromListener($listener);
            }

            yield $listener;
        }
    }

    /**
     * @param $listener
     *
     * @return ListenerInterface
     */
    private function callableFromListener(callable $listener): ListenerInterface
    {
        if (!$listener instanceof ListenerInterface) {
            $listener = $this->mapCallabaleListener($listener);
        }

        return $listener;
    }

    /**
     * @param $listener
     *
     * @return mixed
     */
    private function mapCallabaleListener($listener)
    {
        if (!$this->listenerMap->hasKey($listener)) {
            $callableListener = new CallableListener($listener);
            $this->listenerMap->put($listener, $callableListener);
        }

        return $this->listenerMap->get($listener);
    }

}
