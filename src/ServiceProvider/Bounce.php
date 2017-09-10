<?php

namespace Bounce\Bounce\ServiceProvider;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Emitter;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddleware;
use Pimple\Container;
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
        $pimple[self::MAPPED_LISTENER_COLLECTION] = function() {
            return MappedListeners::create();
        };

        $pimple[self::ACCEPTOR_MIDDLEWARE] = function() {
            return new AcceptorMiddleware();
        };

        $pimple[self::ACCEPTOR] = function(Container $con) {
            return new Acceptor(
                $con[self::ACCEPTOR_MIDDLEWARE],
                $con[self::MAPPED_LISTENER_COLLECTION]
            );
        };

        $pimple[self::EMITTER_MIDDLEWARE] = function(Container $con) {

        };

        $pimple[self::EMITTER] = function(Container $con) {
            return new Emitter(
                $con[self::ACCEPTOR],
                $con[self::EMITTER_MIDDLEWARE]
            ) ;
        };
    }
}