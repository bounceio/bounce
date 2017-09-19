<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce\Middleware\Acceptor;

use Bounce\Emitter\MappedListener\MappedListenerInterface;
use Ds\Stack;
use Generator;
use Psr\Container\ContainerInterface;
use stdClass;

class ContainerMiddleware
{
    const ACCEPTOR_PLUGINS = 'bounce.middleware.acceptor.plugins';

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
     * {@inheritdoc}
     */
    public function __invoke($map, $listener, $priority): MappedListenerInterface
    {
        $stack = $this->executionChain();

        return $stack($this->dtoFrom($map, $listener, $priority));
    }

    /**
     * @return callable
     */
    private function executionChain(): callable
    {
        if (!$this->executionChain) {
            $lastCallable = function ($parts) {
                return $parts;
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
        foreach ($this->container->get(self::ACCEPTOR_PLUGINS) as $plugin) {
            $stack->push($plugin);
        }

        return $stack;
    }

    /**
     * @param $map
     * @param $listener
     * @param $priority
     *
     * @return stdClass
     */
    private function dtoFrom($map, $listener, $priority)
    {
        $listenerToMap              = new stdClass();
        $listenerToMap->map         = $map;
        $listenerToMap->listener    = $listener;
        $listenerToMap->priority    = $priority;

        return $listenerToMap;
    }
}
