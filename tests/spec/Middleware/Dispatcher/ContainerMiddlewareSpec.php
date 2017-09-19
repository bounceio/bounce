<?php

namespace spec\Bounce\Bounce\Middleware\Dispatcher;

use Bounce\Bounce\Middleware\Dispatcher\ContainerMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\NamedEvent;
use Bounce\Emitter\Event\Named;
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

    function it_executes_middleware_plugins_for_an_event(
        ContainerInterface $locator,
        NamedEvent $namedEvent,
        EventInterface $named
    ) {
        $eventName = 'foo.bar';
        $locator->get(ContainerMiddleware::DISPATCHER_PLUGINS)->willReturn([$namedEvent]);
        $namedEvent->__invoke($eventName, Argument::type('callable'))->willReturn($named);
        $this->__invoke($eventName)->shouldReturn($named);
    }

    function it_executes_a_stack_of_middleware_in_order(
        ContainerInterface $locator
    ) {
        $eventName = 'foo.baz';

        $first = new NamedEvent();

        $second = function(Named $named, callable $next) {
            $named->count = 2;

            return $next($named);
        };

        $third = function(Named $named, callable $next) {
            if ($named->count == 2) {
                $named->count = 5;
            }

            return $next($named);
        };


        $locator->get(ContainerMiddleware::DISPATCHER_PLUGINS)->willReturn([$first, $second, $third]);

        $this->__invoke($eventName)->shouldBeAValidEvent(5);
    }
}
