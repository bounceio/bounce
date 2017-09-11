<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\EventQueue;

use EventIO\InterOp\EventInterface;
use SplQueue;

final class EventQueue implements EventQueueInterface
{
    /**
     * @var EventQueue
     */
    private $queue;

    /**
     * @param Iterator|null $events Events to queue
     * @return EventQueue
     */
    public static function create(iterable $events = null)
    {
        $queue = new SplQueue();
        $queue->setIteratorMode(SplQueue::IT_MODE_DELETE);

        $eventQueue = new self($queue);
        if ($events) {
            foreach ($events as $event) {
                $eventQueue->queue($event);
            }
        }

        return $eventQueue;
    }

    /**
     * EventQueue constructor.
     * @param SplQueue $queue An SplQueue to put events into
     */
    private function __construct(SplQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param EventInterface[] ...$events
     * @return EventQueueInterface
     */
    public function queueEvent(EventInterface ...$events): EventQueueInterface
    {
        return $this->queueEvents($events);
    }

    /**
     * @param iterable $events
     * @return EventQueueInterface
     */
    public function queueEvents(iterable $events): EventQueueInterface
    {
        foreach ($events as $event) {
            $this->enqueueEvent($event);
        }

        return $this;
    }

    private function enqueueEvent(EventInterface $event)
    {
        $this->queue->enqueue($event);
    }

    /**
     * @return iterable
     */
    public function events(): iterable
    {
        while (!$this->queue->isEmpty()) {
            yield $this->queue->dequeue();
        }
    }
}
