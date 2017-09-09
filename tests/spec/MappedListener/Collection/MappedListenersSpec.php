<?php

namespace spec\Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use PhpSpec\ObjectBehavior;

class MappedListenersSpec extends ObjectBehavior
{

    function it_is_a_mapped_listener_collection()
    {
        $this->beConstructedThroughCreate();
        $this->shouldHaveType(MappedListenerCollectionInterface::class);
    }

    function it_returns_listeners_for_an_event(
        MappedListenerInterface $mappedListener,
        EventInterface $event
    ) {
        $listener = function(){};

        $mappedListener->listener()->willReturn($listener);
        $mappedListener->matches($event)->willReturn(true);

        $this->beConstructedThroughCreate();
        $this->add($mappedListener);
        $this->listenersFor($event)->shouldIterateAs([$listener]);
    }

    function it_returns_listeners_in_the_correct_order(
        MappedListenerInterface $firstMappedListener,
        MappedListenerInterface $secondMappedListener,
        EventInterface $event
    ) {
        $firstListener  = function() {};
        $secondListener = function() {};

        $firstMappedListener->matches($event)->willReturn(true);
        $secondMappedListener->matches($event)->willReturn(true);

        $firstMappedListener->compare($secondMappedListener)->willReturn(MappedListenerInterface::HIGHER_PRIORITY);
        $secondMappedListener->compare($firstMappedListener)->willReturn(MappedListenerInterface::LOWER_PRIORITY);

        $firstMappedListener->listener()->willReturn($firstListener);
        $secondMappedListener->listener()->willReturn($secondListener);

        $this->beConstructedThroughCreate();
        $this->add($secondMappedListener);
        $this->add($firstMappedListener);
        $this->listenersFor($event)->shouldIterateAs([$firstListener, $secondListener]);
    }
}
