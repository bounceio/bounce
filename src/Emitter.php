<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 *
 * @copyright    Barney Hanlon 2017-09-01
 *
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use EventIO\InterOp\EmitterInterface;
use EventIO\InterOp\EventInterface;
use EventIO\InterOp\ListenerAcceptorInterface;
use EventIO\InterOp\ListenerInterface;

/**
 * Class Emitter
 */
class Emitter implements EmitterInterface, ListenerAcceptorInterface
{
    /**
     * @var AcceptorInterface
     */
    private $acceptor;

    /**
     * Emitter constructor.
     * @param AcceptorInterface $acceptor
     */
    public function __construct(AcceptorInterface $acceptor)
    {
        $this->acceptor = $acceptor;
    }


    /**
     * @param array ...$events The event triggered
     *
     * @return mixed
     */
    public function emit(...$events)
    {
        foreach($events as $event) {
            $this->emitEvent($event);
        }
    }

    /**
     * @param EventInterface $event The event triggered
     *
     * @return mixed
     */
    public function emitEvent(EventInterface $event)
    {
        foreach ($this->acceptor->listenersFor($event) as $listener) {
            $listener->handle($event);
        }
    }

    /**
     * @param string $event The event name to emit
     *
     * @return mixed
     */
    public function emitName($event)
    {
        // TODO: Implement emitName() method.
    }

    /**
     * @param string                     $eventName The name of the event to listen for
     * @param callable|ListenerInterface $listener  A listener or callable
     * @param int                        $priority  Used to prioritise listeners for the same event
     *
     * @return mixed
     */
    public function addListener(
        $eventName,
        $listener,
        $priority = self::PRIORITY_NORMAL
    ) {
        $this->acceptor->addListener($eventName, $listener, $priority);
    }
}
