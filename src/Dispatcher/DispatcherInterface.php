<?php
namespace Bounce\Bounce\Dispatcher;

use Bounce\Bounce\Acceptor\AcceptorInterface;
use EventIO\InterOp\EventInterface;

interface DispatcherInterface
{
    /**
     * @param EventInterface[] ...$events
     * @return DispatcherInterface
     */
    public function enqueue(EventInterface ...$events): DispatcherInterface;

    /**
     * @return bool
     */
    public function isDispatching(): bool;

    /**
     * @param AcceptorInterface $acceptor
     * @return DispatcherInterface
     */
    public function dispatch(AcceptorInterface $acceptor): DispatcherInterface;
}