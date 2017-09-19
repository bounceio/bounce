<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Cartographer\CartographerInterface;
use Bounce\Cartographer\Map\MapInterface;
use Bounce\Cartographer\ServiceProvider\CartographerServiceProvider;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use StdClass;

class CartographySpec extends ObjectBehavior
{
    function let(CartographerInterface $cartographer)
    {
        $this->beConstructedThroughCreate($cartographer);
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

        $cartographer->map(CartographerServiceProvider::GLOB, $mapString)
            ->willReturn($map);

        $parts->map = $mapString;

        $this->__invoke($parts, $next)->shouldReturn($map);
    }
}
