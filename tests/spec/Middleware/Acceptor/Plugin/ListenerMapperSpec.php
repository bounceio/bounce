<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use Bounce\Cartographer\Map\Glob;
use Bounce\Emitter\Acceptor\Acceptor;
use Bounce\Emitter\MappedListener\MappedListenerInterface;
use PhpSpec\ObjectBehavior;
use stdClass;

/**
 * Class ListenerMapperSpec
 */
class ListenerMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AcceptorPluginInterface::class);
    }

    function it_returns_a_mapped_listener() {
        $dto = new stdClass();

        $dto->map       = Glob::create('foo');
        $dto->listener  = function(){};
        $dto->priority  = Acceptor::PRIORITY_NORMAL;

        $next = function($parts) {
            return $parts;
        };

        $this->__invoke($dto, $next)->shouldBeAnInstanceOf(MappedListenerInterface::class);
    }
}
