<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider\Middleware;

use Bounce\Bounce\Middleware\Acceptor\ContainerMiddleware;
use Bounce\Bounce\Middleware\Acceptor\Plugin\Cartography;
use Bounce\Bounce\Middleware\Acceptor\Plugin\ListenerMapper;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class AcceptorServiceProvider
 * @package Bounce\Bounce\ServiceProvider\Middleware
 */
class AcceptorServiceProvider implements ServiceProviderInterface
{
    const MIDDLEWARE                           = 'bounce.middleware.acceptor';
    const MIDDLEWARE_CONTAINER                 = self::MIDDLEWARE.'.container_middleware';
    const MIDDLEWARE_CONTAINER_SERVICE_LOCATOR = self::MIDDLEWARE_CONTAINER.'.service_locator';
    const MIDDLEWARE_PLUGINS                   = self::MIDDLEWARE.'.plugins';
    const MIDDLEWARE_PLUGINS_CARTOGRAPHY       = self::MIDDLEWARE_PLUGINS.'.cartography';
    const MIDDLEWARE_PLUGINS_LISTENER_MAPPER   = self::MIDDLEWARE_PLUGINS.'.listener_mapper';

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
        $pimple[self::MIDDLEWARE_PLUGINS_CARTOGRAPHY] = function() {
            return Cartography::create();
        };

        $pimple[self::MIDDLEWARE_PLUGINS_LISTENER_MAPPER] = function() {
            return new ListenerMapper();
        };

        $pimple[self::MIDDLEWARE_PLUGINS] = function(Container $con) {
            yield $con[self::MIDDLEWARE_PLUGINS_CARTOGRAPHY];
            yield $con[self::MIDDLEWARE_PLUGINS_LISTENER_MAPPER];
        };

        $pimple[self::MIDDLEWARE_CONTAINER_SERVICE_LOCATOR] = function(Container $con) {
            return new ServiceLocator(
                $con,
                [self::MIDDLEWARE_PLUGINS]
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