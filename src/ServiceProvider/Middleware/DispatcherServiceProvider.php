<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider\Middleware;

use Bounce\Bounce\Middleware\Dispatcher\ContainerMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\CallableListeners;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\NamedEvent;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class DispatcherServiceProvider
 * @package Bounce\Bounce\ServiceProvider\Middleware
 */
class DispatcherServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['bounce.middleware.dispatcher.plugins.named_event'] = function() {
            return new NamedEvent();
        };

        $pimple['bounce.middleware.dispatcher.plugins.callable_listeners'] = function() {
            return new CallableListeners();
        };

        $pimple['bounce.middleware.dispatcher.plugins'] = function(Container $con) {
            yield $con['bounce.middleware.dispatcher.plugins.named_event'];
            yield $con['bounce.middleware.dispatcher.plugins.callable_listeners'];
        };

        $pimple['bounce.middleware.dispatcher.container_middleware.service_locator'] = function(Container $con) {
            return new ServiceLocator(
                $con,
                ['bounce.middleware.dispatcher.plugins']
            );
        };

        $pimple['bounce.middleware.dispatcher.container_middleware'] = function(Container $con) {
            return new ContainerMiddleware(
                $con['bounce.middleware.dispatcher.container_middleware.service_locator']
            );
        };

        $pimple['bounce.middleware.dispatcher'] = function(Container $con) {
            return $con['bounce.middleware.dispatcher.container_middleware'];
        };
    }
}