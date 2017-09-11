<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddlewareInterface;
use Bounce\Bounce\Middleware\Acceptor\ContainerMiddleware;
use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use Bounce\Bounce\Middleware\Acceptor\Plugin\ListenerMap;
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

    function it_is_acceptor_middleware()
    {
        $this->shouldHaveType(AcceptorMiddlewareInterface::class);
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

        $this->listenerAdd($map, $listener, Acceptor::PRIORITY_NORMAL)
            ->shouldReturn($mappedListener);
    }
}
