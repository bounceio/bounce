<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\DispatchLoop\DispatchLoop;
use Bounce\Bounce\Event\Named;
use Bounce\Bounce\Map\Glob;
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
    return new CallableListeners($con['mapped_listeners']);
};

$container[ContainerMiddleware::QUEUE_PLUGINS] = function($con) {
    return [
        $con['named_event'],
        $con['callable_listeners'],
    ];
};

$serviceLocator = new \Pimple\Psr11\ServiceLocator(
    $container,
    [ContainerMiddleware::QUEUE_PLUGINS]
);

$container['dispatcher_middleware'] = new ContainerMiddleware(
    $serviceLocator
);

$eventName = 'foo';

$map = Glob::create($eventName);

$listeners = [];

for ($i=0; $i<10000; $i++) {
    foreach(['foo', 'bar', 'baz'] as $str) {
        $listeners[] = function(EventInterface $event) use($i, $str) {
            echo "$i: $str\n";
        };
    }
}

foreach ($listeners as $listener) {
    $container['mapped_listeners']->add(
        MappedListener::create(
            $map,
            $listener,
            Acceptor::PRIORITY_NORMAL
        )
    );
}

$event                  = Named::create($eventName);

for ($i=0; $i<100; $i++) {
    $dto                    = new stdClass();
    $dto->event     = $event;
    $dto->listeners = $container['mapped_listeners']->listenersFor($event);
    $dispatchLoop   = DispatchLoop::fromDto($container['dispatcher_middleware']->dispatch($dto));
    $dispatchLoop->dispatch();
}
