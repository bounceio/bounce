<?php

namespace spec\Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Middleware\Acceptor\Plugin\AcceptorPluginInterface;
use PhpSpec\ObjectBehavior;

class ListenerMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AcceptorPluginInterface::class);
    }
}
