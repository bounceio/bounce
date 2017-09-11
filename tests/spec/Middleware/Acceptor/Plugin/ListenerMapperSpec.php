<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Map\Glob;
use Bounce\Bounce\Map\MapInterface;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
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
