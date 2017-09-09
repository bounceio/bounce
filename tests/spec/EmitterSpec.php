<?php

namespace spec\Bounce\Bounce;

use ArrayIterator;
use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\Listener\CallableListener;
use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Traversable;

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

    function it_dispatches_a_single_event(
        EventInterface $event,
        $acceptor
    ) {
        $eventName = 'foo';
        $listener = new CallableListener(function(){});

        $acceptor->addListener($eventName, $listener, ListenerAcceptorInterface::PRIORITY_NORMAL)
            ->shouldBeCalled();
        $acceptor->listenersFor($event)->will(function($event) use($listener) {
           yield $listener;
        });

        $this->addListener($eventName, $listener);

        $this->emitEvent($event);
    }

    function it_queues_events(
        EventInterface $firstEvent,
        EventInterface $secondEvent,
        EventInterface $thirdEvent,
        $acceptor
    ) {
        $listeners = new ArrayIterator([]);

        $acceptor->listenersFor($firstEvent)->willReturn($listeners)
            ->shouldBeCalled();

        $acceptor->listenersFor($secondEvent)->willReturn($listeners)
            ->shouldBeCalled();

        $acceptor->listenersFor($thirdEvent)->willReturn($listeners)
            ->shouldBeCalled();


        $this->emit($firstEvent, $secondEvent, $thirdEvent);
    }
}
