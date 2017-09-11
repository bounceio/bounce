<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\CartographerInterface;
use Bounce\Bounce\Map\MapInterface;
use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use StdClass;

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

        $parts = new StdClass();

        $next = function($parts) {
            return $parts->map;
        };

        $cartographer->map(Cartographer::MAP_GLOB, $mapString)
            ->willReturn($map);

        $parts->map = $mapString;

        $this->__invoke($parts, $next)->shouldReturn($map);
    }
}
