<?php

namespace spec\Bounce\Bounce\Middleware\Emitter\Plugin;

use Bounce\Bounce\Middleware\Emitter\Plugin\EmitterPluginInterface;
use PhpSpec\ObjectBehavior;

class NamedEventSpec extends ObjectBehavior
{
    function it_is_an_emitter_plugin()
    {
        $this->shouldHaveType(EmitterPluginInterface::class);
    }
}
