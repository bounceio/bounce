<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\CartographerInterface;
use Bounce\Bounce\Map\MapInterface;
use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;

class ListenerMapSpec extends ObjectBehavior
{
    function let(CartographerInterface $cartographer)
    {
        $this->beConstructedThroughCreate($cartographer);
    }

    function it_is_an_acceptor_plugin()
    {
        $this->shouldHaveType(AcceptorPluginInterface::class);
    }

    function it_returns_a_map_for_an_event_name(
        CartographerInterface $cartographer,
        ListenerInterface $listener,
        MapInterface $map
    ) {
        $mapString = 'foo.bar.*';

        $next = function($parts) {
            return $parts[0];
        };

        $cartographer->map(Cartographer::MAP_GLOB, $mapString)
            ->willReturn($map);

        $priority = Acceptor::PRIORITY_NORMAL;

        $this->__invoke([$mapString, $listener, $priority], $next)->shouldReturn($map);
    }
}
