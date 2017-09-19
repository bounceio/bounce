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
    const MIDDLEWARE                            = 'bounce.middleware.dispatcher';
    const MIDDLEWARE_CONTAINER                  = self::MIDDLEWARE.'.container_middleware';
    const MIDDLEWARE_CONTAINER_SERVICE_LOCATOR  = self::MIDDLEWARE_CONTAINER.'.service_locator';
    const MIDDLEWARE_PLUGINS_CALLABLE_LISTENERS = self::MIDDLEWARE_PLUGINS.'.callable_listeners';
    const MIDDLEWARE_PLUGINS_NAMED_EVENT        = self::MIDDLEWARE_PLUGINS.'.named_event';

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
        $pimple[self::MIDDLEWARE_PLUGINS_NAMED_EVENT] = function() {
            return new NamedEvent();
        };

        $pimple[self::MIDDLEWARE_PLUGINS_CALLABLE_LISTENERS] = function() {
            return new CallableListeners();
        };

        $pimple[ContainerMiddleware::DISPATCHER_PLUGINS] = function(Container $con) {
            yield $con[self::MIDDLEWARE_PLUGINS_NAMED_EVENT];
            yield $con[self::MIDDLEWARE_PLUGINS_CALLABLE_LISTENERS];
        };

        $pimple[self::MIDDLEWARE_CONTAINER_SERVICE_LOCATOR] = function(Container $con) {
            return new ServiceLocator(
                $con,
                [ContainerMiddleware::DISPATCHER_PLUGINS]
            );
        };

        $pimple[self::MIDDLEWARE_CONTAINER] = function(Container $con) {
            return new ContainerMiddleware(
                $con[self::MIDDLEWARE_CONTAINER_SERVICE_LOCATOR]
            );
        };

        $pimple[self::MIDDLEWARE] = function(Container $con) {
            return $con[self::MIDDLEWARE_CONTAINER];
        };
    }
}
