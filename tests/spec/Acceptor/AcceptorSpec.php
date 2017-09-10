<?php

namespace spec\Bounce\Bounce\Acceptor;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\Listener\CallableListener;
use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\Middleware\Acceptor\AcceptorMiddlewareInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerInterface;
use PhpSpec\ObjectBehavior;
use SplQueue;

class AcceptorSpec extends ObjectBehavior
{
    function let(
        AcceptorMiddlewareInterface $middleware,
        MappedListenerCollectionInterface $listenerCollection

    ) {
        $this->beConstructedWith($middleware, $listenerCollection);
    }

    function it_is_a_listener_acceptor()
    {
        $this->shouldHaveType(AcceptorInterface::class);
    }

    function it_returns_a_listener(
        EventInterface $event,
        MappedListenerInterface $mappedListener,
        AcceptorMiddlewareInterface $middleware,
        MappedListenerCollectionInterface $listenerCollection
    ) {
        $eventName = 'foo';

        $callable = function() {};
        $queue = new SplQueue();
        $listener = new CallableListener($callable);
        $queue->enqueue($listener);

        $middleware->listenerAdd(
            $eventName,
            $callable,
            AcceptorInterface::PRIORITY_NORMAL
            )->willReturn($mappedListener);

        $listenerCollection->add($mappedListener)->shouldBeCalled();

        $middleware->listenersFor($event, $listenerCollection)->willReturn($queue);

        $this->addListener(
            $eventName,
            $callable
        );

        $this->listenersFor($event)->shouldIterateAs($queue);
    }
}
