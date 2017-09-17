<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\DispatchLoop\DispatchLoop;
use Bounce\Bounce\Event\Named;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\MappedListener\MappedListener;
use Bounce\Bounce\Middleware\Dispatcher\ContainerMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\CallableListeners;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\NamedEvent;
use EventIO\InterOp\EventInterface;
use Pimple\Container;

require_once __DIR__.'/../vendor/autoload.php';

define('NUMBER_EVENTS', 1000);
define('NUMBER_LISTENERS', 2000);

$container = new Pimple\Container();

$container['named_event'] = function() {
    return new NamedEvent();
};

$container['mapped_listeners'] = function(): MappedListeners {
    return MappedListeners::create();
};

$container['callable_listeners'] = function(Container $con) {
    return new CallableListeners();
};

$container[ContainerMiddleware::QUEUE_PLUGINS] = function($con) {
    yield $con['named_event'];
    yield $con['callable_listeners'];
};

$container['dispatcher_middleware'] = function($con) {
    $serviceLocator = new \Pimple\Psr11\ServiceLocator(
        $con,
        [ContainerMiddleware::QUEUE_PLUGINS]
    );

    return new ContainerMiddleware(
        $serviceLocator
    );
};

$eventNames = ['foo', 'bar', 'baz', 'bal', 'bom'];
$events = [];

foreach ($eventNames as $eventName) {
  $events[] = Named::create($eventName);
}

$maps = [];

foreach ($eventNames as $eventName) {
  $maps[] = new Bounce\Bounce\Map\Name($eventName);
}
$eventCount = count($events);

$mappedListeners = $container['mapped_listeners'];

$priorities = [
    Acceptor::PRIORITY_LOW,
    Acceptor::PRIORITY_NORMAL,
    Acceptor::PRIORITY_HIGH,
    Acceptor::PRIORITY_URGENT,
    Acceptor::PRIORITY_CRITICAL
];

$counter        = new stdClass();
$counter->count = 0;

$listeners = function($maps, $priorities, $eventNames) use($counter, $eventCount) {
    for ($i=0; $i<NUMBER_LISTENERS; $i++) {
        $eventName = $eventNames[$i % $eventCount];
        $priority  = $priorities[array_rand($priorities)];

        $listener = function(EventInterface $event)
        use($eventName, $i, $priority, $counter) {
            $count = $counter->count;
            echo "$count: $priority : $eventName:$i\n";
            $counter->count++;
        };

        yield MappedListener::create(
            $maps[array_rand($maps)],
            $listener,
            $priority
        );
    }
};

$mappedListeners->addListeners($listeners($maps, $priorities, $eventNames));

$dispatcherMiddleware = $container['dispatcher_middleware'];

for ($i=0; $i<NUMBER_EVENTS; $i++) {
    $event          = $events[$i % $eventCount];
    $dto            = new stdClass();
    $dto->event     = $event;
    $dto->listeners = $mappedListeners->listenersFor($event);

    $dispatchLoop   = DispatchLoop::fromDto($dispatcherMiddleware->dispatch($dto));
    $dispatchLoop->dispatch();
}
var_dump($counter);
