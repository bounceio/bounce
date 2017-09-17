<?php
namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;

class QueuePriorityFilter
{
    private $priority;


    public function filter($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param MappedListenerInterface $mappedListener
     * @param $priority
     * @return bool
     */
    public function __invoke(MappedListenerInterface $mappedListener, $priority)
    {
        return $priority === $this->priority;
    }
}