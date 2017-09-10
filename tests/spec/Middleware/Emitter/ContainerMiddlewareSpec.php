<?php

namespace spec\Bounce\Bounce\Middleware\Emitter;

use Bounce\Bounce\Event\Named;
use Bounce\Bounce\Middleware\Emitter\ContainerMiddleware;
use Bounce\Bounce\Middleware\Emitter\EmitterMiddlewareInterface;
use Bounce\Bounce\Middleware\Emitter\Plugin\NamedEvent;
use EventIO\InterOp\EventInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

class ContainerMiddlewareSpec extends ObjectBehavior
{
    function getMatchers(): array
    {
        return [
            'beAValidEvent' => function(Named $named, $counter) {
                return $named->count == $counter;
            }
        ];
    }

    function let(ContainerInterface $locator)
    {
        $this->beConstructedWith($locator);
    }

    function it_is_emitter_middleware()
    {
        $this->shouldHaveType(EmitterMiddlewareInterface::class);
    }

    function it_executes_middleware_plugins_for_an_event(
        ContainerInterface $locator,
        NamedEvent $namedEvent,
        EventInterface $named
    ) {
        $eventName = 'foo.bar';
        $locator->get(ContainerMiddleware::QUEUE_PLUGINS)->willReturn([$namedEvent]);
        $namedEvent->__invoke($eventName, Argument::type('callable'))->willReturn($named);
        $this->queue($eventName)->shouldReturn($named);
    }

    function it_executes_a_stack_of_middleware_in_order(
        ContainerInterface $locator
    ) {
        $eventName = 'foo.baz';

        $first = new NamedEvent();

        $second = function(Named $named, $next) {
            $named->count = 2;

            return $next($named);
        };

        $third = function(Named $named, $next) {
            if ($named->count == 2) {
                $named->count = 5;
            }

            return $next($named);
        };


        $locator->get(ContainerMiddleware::QUEUE_PLUGINS)->willReturn([$first, $second, $third]);

        $this->queue($eventName)->shouldBeAValidEvent(5);
    }
}
