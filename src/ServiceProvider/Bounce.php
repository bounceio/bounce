<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Dispatcher\Dispatcher;
use Bounce\Bounce\Emitter;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddleware;
use Bounce\Bounce\Middleware\Emitter\ContainerMiddleware;
use Bounce\Bounce\ServiceProvider\Middleware\AcceptorServiceProvider;
use Bounce\Bounce\ServiceProvider\Middleware\DispatcherServiceProvider;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class Bounce
 */
class Bounce implements ServiceProviderInterface
{
    const EMITTER                       = 'bounce.emitter';
    const ACCEPTOR                      = 'bounce.acceptor';
    const ACCEPTOR_MIDDLEWARE           = self::ACCEPTOR.'.middleware';
    const DISPATCHER                    = 'bounce.dispatcher';
    const DISPATCHER_MIDDLEWARE         = self::DISPATCHER.'.middleware';

    const MAPPED_LISTENER_COLLECTION    = 'bounce.mapped_listener_collection';

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
        $pimple[self::MAPPED_LISTENER_COLLECTION] = function () {
            return MappedListeners::create();
        };

        $pimple[self::ACCEPTOR_MIDDLEWARE] = function(Container $con) {
            if (!$con->offsetExists(AcceptorServiceProvider::MIDDLEWARE)) {
                $con->register(new AcceptorServiceProvider());
            }

            return $con[AcceptorServiceProvider::MIDDLEWARE];
        };

        $pimple[self::DISPATCHER_MIDDLEWARE] = function(Container $con) {
            if (!$con->offsetExists(DispatcherServiceProvider::MIDDLEWARE)) {
                $con->register(new DispatcherServiceProvider());
            }

            return $con[DispatcherServiceProvider::MIDDLEWARE];
        };

        $pimple[self::ACCEPTOR] = function(Container $con) {
            return Acceptor::create(
                $con[self::ACCEPTOR_MIDDLEWARE],
                $con[self::MAPPED_LISTENER_COLLECTION]
            );
        };

        $pimple[self::DISPATCHER] = function(Container $con) {
            return Dispatcher::create(
                $con[self::DISPATCHER_MIDDLEWARE]
            );
        };

        $pimple[self::EMITTER] = function (Container $con) {
            return new Emitter(
                $con[self::ACCEPTOR],
                $con[self::DISPATCHER]
            ) ;
        };
    }
}
