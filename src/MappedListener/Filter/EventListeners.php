<?php

namespace Bounce\Bounce\MappedListener\Filter;

use Bounce\Bounce\MappedListener\MappedListenerInterface;

class EventListeners
{
    private $event;

    public function __invoke(MappedListenerInterface $mappedListener)
    {
        return $mappedListener->matches($this->event);
    }


    public function filter($event)
    {
        $this->event = $event;

        return $this;
    }
}