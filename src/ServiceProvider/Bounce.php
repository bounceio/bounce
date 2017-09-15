<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Emitter;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddleware;
use Bounce\Bounce\Middleware\Emitter\ContainerMiddleware;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

/**
 * Class Bounce
 */
class Bounce implements ServiceProviderInterface
{
    const EMITTER                       = 'bounce.emitter';
    const EMITTER_MIDDLEWARE            = self::EMITTER . '.middleware';
    const ACCEPTOR                      = 'bounce.acceptor';
    const ACCEPTOR_MIDDLEWARE           = self::ACCEPTOR . '.middleware';
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

//        $pimple[self::ACCEPTOR_MIDDLEWARE] = function () {
//            return new ContainerMiddleware();
//        };

        $pimple[self::ACCEPTOR] = function (Container $con) {
            return Acceptor::create(
                $con[self::ACCEPTOR_MIDDLEWARE],
                $con[self::MAPPED_LISTENER_COLLECTION]
            );
        };

        $pimple[self::EMITTER_MIDDLEWARE] = function (Container $con) {
            $serviceLocator = new ServiceLocator($con, []);
            return new ContainerMiddleware($serviceLocator);
        };

        $pimple[self::EMITTER] = function (Container $con) {
            return new Emitter(
                $con[self::ACCEPTOR],
                $con[self::EMITTER_MIDDLEWARE]
            ) ;
        };
    }
}
