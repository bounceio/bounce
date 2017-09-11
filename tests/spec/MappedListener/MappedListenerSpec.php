<?php

namespace spec\Bounce\Bounce\MappedListener;

use Bounce\Bounce\MappedListener\MappedListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MappedListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MappedListener::class);
    }
}
