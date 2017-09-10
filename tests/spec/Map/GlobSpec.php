<?php

namespace spec\Bounce\Bounce\Map;

use Bounce\Bounce\Map\Glob;
use EventIO\InterOp\EventInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlobSpec extends ObjectBehavior
{
    function it_returns_true_if_the_event_matches_exactly(
        EventInterface $event
    ) {
        $eventName = 'foo.bar';
        $event->name()->willReturn($eventName);
        $this->beConstructedWith($eventName);
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_returns_true_if_the_event_matches_a_pattern(
        EventInterface $event
    ) {
        $eventName = 'foo.bar';
        $event->name()->willReturn($eventName);
        $this->beConstructedWith('foo.*');
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_returns_false_if_the_event_does_not_match(
        EventInterface $event
    ) {
        $eventName = 'foo.bar';
        $event->name()->willReturn($eventName);
        $this->beConstructedWith('bar.*');
        $this->isMatch($event)->shouldReturn(false);
    }

    function it_accepts_a_wildcard_that_will_match_any_event(
        EventInterface $event
    ) {
        $event->name()->willReturn('dhfsiusjhgdfkjsdfkhsgdbvhblrwhjbdhfbwehsnbdf');
        $this->beConstructedWith('*');
        $this->isMatch($event)->shouldReturn(true);
    }
}
