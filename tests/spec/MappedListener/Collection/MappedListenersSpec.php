<?php

namespace spec\Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\Collection\MappedListenerCollectionInterface;
use PhpSpec\ObjectBehavior;

class MappedListenersSpec extends ObjectBehavior
{
    function it_is_a_mapped_listener_collection()
    {
        $this->shouldHaveType(MappedListenerCollectionInterface::class);
    }
}
