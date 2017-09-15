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

$serviceLocator = new \Pimple\Psr11\ServiceLocator(
    $container,
    [ContainerMiddleware::QUEUE_PLUGINS]
);

$container['dispatcher_middleware'] = new ContainerMiddleware(
    $serviceLocator
);

$eventName = 'foo';

$map = new Bounce\Bounce\Map\Name($eventName);

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

$listeners = function($map, $priorities) use($counter) {
    for ($i=0; $i<1000; $i++) {
        foreach(['foo', 'bar', 'baz'] as $str) {
            $priority = array_rand($priorities);

            $listener = function(EventInterface $event)
            use($str, $priority, $counter) {
                $count = $counter->count;
                echo "$count: $priority : $str\n";
                $counter->count++;
            };

            yield MappedListener::create(
                $map,
                $listener,
                $priority
            );
        }
    }
};

$mappedListeners->addListeners($listeners($map, $priorities));

$event = Named::create($eventName);

$dispatcherMiddleware = $container['dispatcher_middleware'];

for ($i=0; $i<10000; $i++) {
    $dto            = new stdClass();
    $dto->event     = $event;
    $dto->listeners = $mappedListeners->listenersFor($event);

    $dispatchLoop   = DispatchLoop::fromDto($dispatcherMiddleware->dispatch($dto));
    $dispatchLoop->dispatch();
}
var_dump($counter);
