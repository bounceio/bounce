<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor;

use Bounce\Bounce\Middleware\Acceptor\ContainerMiddleware;
use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use Bounce\Emitter\Acceptor\Acceptor;
use Bounce\Emitter\MappedListener\MappedListenerInterface;
use Bounce\Emitter\Middleware\AcceptorMiddlewareInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerMiddlewareSpec
 */
class ContainerMiddlewareSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator)
    {
        $this->beConstructedWith($locator);
    }

    function it_executes_middleware_plugins_for_a_listener_being_added(
        ContainerInterface $locator,
        AcceptorPluginInterface $plugin,
        MappedListenerInterface $mappedListener,
        ListenerInterface $listener
    ) {
        $locator->get(ContainerMiddleware::LISTENER_PLUGINS)->willReturn([$plugin]);
        $map = 'foo.*';
        $plugin->__invoke(
            Argument::type(\StdClass::class),
            Argument::type('callable')
        )->willReturn($mappedListener);

        $this->__invoke($map, $listener, Acceptor::PRIORITY_NORMAL)
            ->shouldReturn($mappedListener);
    }
}
