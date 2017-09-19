<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider;

use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\ServiceProvider\Middleware\AcceptorServiceProvider;
use Bounce\Bounce\ServiceProvider\Middleware\DispatcherServiceProvider;
use Bounce\Emitter\ServiceProvider\EmitterServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class Bounce
 */
class Bounce implements ServiceProviderInterface
{
    const EMITTER                       = EmitterServiceProvider::EMITTER;
    const ACCEPTOR_MIDDLEWARE           = EmitterServiceProvider::ACCEPTOR_MIDDLEWARE;
    const DISPATCHER_MIDDLEWARE         = EmitterServiceProvider::DISPATCHER_MIDDLEWARE;

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
        $pimple->register(new EmitterServiceProvider());

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
    }
}
