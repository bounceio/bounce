<?php

namespace spec\Bounce\Bounce;

use Bounce\Bounce\Emitter;
use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmitterSpec extends ObjectBehavior
{
    function it_is_an_event_emitter()
    {
        $this->shouldHaveType(EmitterInterface::class);
    }

    function it_is_a_listener_acceptor()
    {
        $this->shouldHaveType(ListenerAcceptorInterface::class);
    }
}
