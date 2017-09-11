<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce\Middleware\Acceptor;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Psr\Container\ContainerInterface;
use stdClass;
use Traversable;

class ContainerMiddleware implements AcceptorMiddlewareInterface
{
    const LISTENER_PLUGINS = 'bounce.middleware.acceptor.plugins.add_listener';

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
    public function listenerAdd($map, $listener, $priority): MappedListenerInterface
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
            $lastCallable = function (EventInterface $event) {
                return $event;
            };

            $plugins = $this->container->get(self::LISTENER_PLUGINS);

            while ($plugin = array_pop($plugins)) {
                $lastCallable = function ($dto) use ($plugin, $lastCallable) {
                    return $plugin($dto, $lastCallable);
                };
            }

            $this->executionChain = $lastCallable;
        }

        return $this->executionChain;
    }

    private function dtoFrom($map, $listener, $priority)
    {
        $listenerToMap              = new stdClass();
        $listenerToMap->map         = $map;
        $listenerToMap->listener    = $listener;
        $listenerToMap->priority    = $priority;

        return $listenerToMap;
    }
}
