<?php

namespace Bounce\Bounce\Middleware\Emitter;

use EventIO\InterOp\EventInterface;
use Psr\Container\ContainerInterface;

class ContainerMiddleware implements EmitterMiddlewareInterface
{
    const QUEUE_PLUGINS = 'bounce.middleware.emitter.plugins.event';

    /**
     * @var ContainerInterface
     */
    private $container;

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
     * @param $event
     * @return EventInterface
     */
    public function queue($event): EventInterface
    {
        $stack = $this->executionChain();

        return $stack($event);
    }

    /**
     * @return callable
     */
    private function executionChain(): callable
    {
        if (!$this->executionChain) {
            $lastCallable = function(EventInterface $event) {
                return $event;
            };

            $plugins = $this->container->get(self::QUEUE_PLUGINS);

            while($plugin = array_pop($plugins)) {
                $lastCallable = function ($event) use ($plugin, $lastCallable) {
                    return $plugin($event, $lastCallable);
                };
            }

            $this->executionChain = $lastCallable;
        }
        return $this->executionChain;
    }
}
