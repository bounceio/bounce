<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher;

use Ds\Stack;
use Generator;
use Psr\Container\ContainerInterface;

class ContainerMiddleware implements DispatcherMiddlewareInterface
{
    const QUEUE_PLUGINS = 'bounce.middleware.emitter.plugins.event';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var callable
     */
    private $executionChain;

    /**
     * ContainerMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $eventDispatchLoop
     *
     * @return mixed
     */
    public function dispatch(
        $eventDispatchLoop
    ) {
        $excecutionChain = $this->executionChain();

        return $excecutionChain($eventDispatchLoop);
    }

    /**
     * @return callable
     */
    private function executionChain(): callable
    {
        if (!$this->executionChain) {
            $lastCallable = function ($dispatchLoop) {
                return $dispatchLoop;
            };

            foreach ($this->pluginStack() as $plugin) {
                $lastCallable = function ($eventDispatchLoop) use ($plugin, $lastCallable) {
                    return $plugin($eventDispatchLoop, $lastCallable);
                };
            }

            $this->executionChain = $lastCallable;
        }

        return $this->executionChain;
    }

    private function pluginStack(): Generator
    {
        $plugins = $this->plugins();

        while (!$plugins->isEmpty()) {
            yield $plugins->pop();
        }
    }

    /**
     * @return \Ds\Stack
     */
    private function plugins(): Stack
    {
        $stack = new Stack();
        foreach ($this->container->get(self::QUEUE_PLUGINS) as $plugin) {
            $stack->push($plugin);
        }

        return $stack;
    }
}
