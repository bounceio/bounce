<?php

namespace spec\Bounce\Bounce\Middleware\Emitter\Plugin;

use Bounce\Bounce\Event\Named;
use Bounce\Bounce\Middleware\Emitter\Plugin\EmitterPluginInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NamedEventSpec extends ObjectBehavior
{
    function getMatchers(): array
    {
        return [
            'beAValidNamedEvent'    => function(Named $named, $name) {
                return ($named->name() === $name);
            }
        ];
    }

    function it_is_an_emitter_plugin()
    {
        $this->shouldHaveType(EmitterPluginInterface::class);
    }

    function it_creates_a_named_event_from_a_string()
    {
        $eventName = 'simple.event';

        $next = function(Named $named) {
            return $named;
        };

        $this->__invoke($eventName, $next)->shouldBeAValidNamedEvent($eventName);
    }
}
