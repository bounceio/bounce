<?php

namespace spec\Bounce\Bounce\Cartographer;

use Bounce\Bounce\Cartographer\CartographerInterface;
use PhpSpec\ObjectBehavior;

class CartographerSpec extends ObjectBehavior
{
    function it_is_a_cartographer()
    {
        $this->shouldHaveType(CartographerInterface::class);
    }
}
