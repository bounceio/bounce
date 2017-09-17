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
        $pimple['bounce.middleware.acceptor.plugins.cartography'] = function() {
            return Cartography::create();
        };

        $pimple['bounce.middleware.acceptor.plugins.listener_mapper'] = function() {
            return new ListenerMapper();
        };

        $pimple['bounce.middleware.acceptor.plugins'] = function(Container $con) {
            yield $con['bounce.middleware.acceptor.plugins.cartography'];
            yield $con['bounce.middleware.acceptor.plugins.listener_mapper'];
        };

        $pimple['bounce.middleware.acceptor.container_middleware.service_locator'] = function(Container $con) {
            return new ServiceLocator(
                $con,
                ['bounce.middleware.acceptor.plugins']
            );
        };

        $pimple['bounce.middleware.acceptor.container_middleware'] = function(Container $con) {
            return new ContainerMiddleware(
                $con['bounce.middleware.acceptor.container_middleware.service_locator']
            );
        };

        $pimple['bounce.middleware.acceptor'] = function(Container $con) {
            return $con['bounce.middleware.acceptor.container_middleware'];
        };
    }
}