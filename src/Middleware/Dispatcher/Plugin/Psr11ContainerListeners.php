<?php
namespace Bounce\Bounce\Middleware\Dispatcher\Plugin;

use Generator;
use Psr\Container\ContainerInterface;

class Psr11ContainerListeners
{
    /**
     * @var ContainerInterface
     */
    private $listenerContainer;

    /**
     * Psr11ContainerListeners constructor.
     *
     * @param ContainerInterface $listenerContainer
     */
    public function __construct(ContainerInterface $listenerContainer)
    {
        $this->listenerContainer = $listenerContainer;
    }

    /**
     * @param          $eventDispatchLoop
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke($eventDispatchLoop, callable $next)
    {
        $eventDispatchLoop->listeners = $this->containerize($eventDispatchLoop->listeners);
        return $next($eventDispatchLoop);
    }

    /**
     * @param iterable $listeners
     *
     * @return Generator
     */
    public function containerize(iterable $listeners): Generator
    {
        foreach ($listeners as $listener) {
            yield $this->containizeListener($listener);
        }
    }

    /**
     * @param $listener
     *
     * @return mixed
     */
    private function containizeListener($listener)
    {
        if (!is_object($listener) && (is_string($listener))) {
            if ($this->listenerContainer->has($listener)) {
                $listener = $this->listenerContainer->get($listener);
            }
        }

        return $listener;
    }
}
