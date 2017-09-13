<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Dispatcher;

use Bounce\Bounce\Acceptor\AcceptorInterface;
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
        $stack = $this->executionChain();

        return $stack($eventDispatchLoop);
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

            $plugins = $this->container->get(self::QUEUE_PLUGINS);

            while ($plugin = array_pop($plugins)) {
                $lastCallable = function ($eventDispatchLoop) use ($plugin, $lastCallable) {
                    return $plugin($eventDispatchLoop, $lastCallable);
                };
            }

            $this->executionChain = $lastCallable;
        }

        return $this->executionChain;
    }
}
