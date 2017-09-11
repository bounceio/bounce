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
        $this->beConstructedThroughCreate($eventName);
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_returns_true_if_the_event_matches_a_pattern(
        EventInterface $event
    ) {
        $eventName = 'foo.bar';
        $event->name()->willReturn($eventName);
        $this->beConstructedThroughCreate('foo.*');
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_returns_false_if_the_event_does_not_match(
        EventInterface $event
    ) {
        $eventName = 'foo.bar';
        $event->name()->willReturn($eventName);
        $this->beConstructedThroughCreate('bar.*');
        $this->isMatch($event)->shouldReturn(false);
    }

    function it_can_check_multiple_patterns(
        EventInterface $event
    ) {
        $event->name()->willReturn('baz.bop');

        $this->beConstructedThroughFromIterable(['foo.baz', 'foo.bam', 'baz.*']);
        $this->isMatch($event)->shouldReturn(true);
        $event->name()->willReturn('foo.bam');
        $this->isMatch($event)->shouldReturn(true);
    }

    function it_accepts_a_wildcard_that_will_match_any_event(
        EventInterface $event
    ) {
        $event->name()->willReturn('dhfsiusjhgdfkjsdfkhsgdbvhblrwhjbdhfbwehsnbdf');
        $this->beConstructedThroughCreate('*');
        $this->isMatch($event)->shouldReturn(true);
    }
}
