<?php

namespace spec\Bounce\Bounce\Middleware\Emitter\Plugin;

use Bounce\Emitter\Event\Named;

use PhpSpec\ObjectBehavior;
use stdClass;

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

    function it_creates_a_named_event_from_a_string()
    {
        $dto        = new stdClass();
        $eventName  = 'simple.event';
        $dto->event = $eventName;

        $next = function(Named $named) {
            return $named;
        };

        $this->__invoke($eventName, $next)->shouldBeAValidNamedEvent($eventName);
    }
}
