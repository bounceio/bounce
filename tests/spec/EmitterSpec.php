<?php

namespace spec\Bounce\Bounce;

use Bounce\Bounce\Listener\AcceptorInterface;
use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;

class EmitterSpec extends ObjectBehavior
{
    function let(AcceptorInterface $acceptor)
    {
        $this->beConstructedWith($acceptor);
    }

    function it_is_an_event_emitter()
    {
        $this->shouldHaveType(EmitterInterface::class);
    }

    function it_is_a_listener_acceptor()
    {
        $this->shouldHaveType(ListenerAcceptorInterface::class);
    }

    function it_dispatches_events(EventInterface $event, ListenerInterface $listener, $acceptor)
    {
        $eventName = 'foo';

        $acceptor->addListener($eventName, $listener, ListenerAcceptorInterface::PRIORITY_NORMAL)
            ->shouldBeCalled();
        $acceptor->listenersFor($event)->willReturn([$listener]);
        $listener->handle($event)->shouldBeCalled();

        $this->addListener($eventName, $listener);

        $this->emitEvent($event);
    }
}
