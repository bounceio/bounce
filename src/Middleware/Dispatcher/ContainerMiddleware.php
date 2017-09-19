<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher;

use Bounce\Emitter\DispatchLoop\DispatchLoop;
use Ds\Stack;
use Generator;
use Psr\Container\ContainerInterface;

class ContainerMiddleware
{
    const DISPATCHER_PLUGINS = 'bounce.middleware.dispatcher.plugins';

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
    public function __invoke($eventDispatchLoop) {
        $executionChain = $this->executionChain();

        return $executionChain($eventDispatchLoop);
    }

    /**
     * @return callable
     */
    private function executionChain(): callable
    {
        if (!$this->executionChain) {
            $lastCallable = function ($dispatchLoop) {
                if (!$dispatchLoop instanceof DispatchLoop) {
                    $dispatchLoop = DispatchLoop::fromDto($dispatchLoop);
                }

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
        foreach ($this->container->get(self::DISPATCHER_PLUGINS) as $plugin) {
            $stack->push($plugin);
        }

        return $stack;
    }
}
