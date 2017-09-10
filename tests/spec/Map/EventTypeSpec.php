<?php

namespace spec\Bounce\Bounce\Map;

use Bounce\Bounce\Event\Named;
use EventIO\InterOp\EventInterface;
use PhpSpec\ObjectBehavior;

class EventTypeSpec extends ObjectBehavior
{
    function it_returns_true_if_the_event_is_of_the_correct_type()
    {
        $event = Named::create('mock');
        $this->beConstructedWith(Named::class);
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_returns_false_if_the_event_is_not_of_the_correct_type(
        EventInterface $event
    ) {
        $this->beConstructedWith(Named::class);
        $this->isMatch($event)->shouldReturn(false);
    }
}
