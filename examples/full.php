<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Dispatcher\Dispatcher;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;

use Bounce\Bounce\Middleware\Acceptor\Plugin\Cartography;
use Bounce\Bounce\Middleware\Acceptor\Plugin\ListenerMapper;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\CallableListeners;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\NamedEvent;
use Pimple\Container;
use Bounce\Bounce\Middleware\Acceptor\ContainerMiddleware as AcceptorMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\ContainerMiddleware as DispatcherMiddleware;

require_once __DIR__.'/../vendor/autoload.php';

$pimple = new Container();

$pimple[AcceptorMiddleware::LISTENER_PLUGINS] = function() {
  yield Cartography::create();
  yield new ListenerMapper();
};

$container = new Pimple\Psr11\Container($pimple);

$pimple['named_event'] = function() {
    return new NamedEvent();
};

$pimple['mapped_listeners'] = function(): MappedListeners {
    return MappedListeners::create();
};

$pimple['callable_listeners'] = function() {
    return new CallableListeners();
};

$pimple[DispatcherMiddleware::QUEUE_PLUGINS] = function($con) {
    yield $con['named_event'];
    yield $con['callable_listeners'];
};

$pimple['dispatcher_middleware'] = function($con) {
    $serviceLocator = new \Pimple\Psr11\ServiceLocator(
        $con,
        [DispatcherMiddleware::QUEUE_PLUGINS]
    );

    return new DispatcherMiddleware(
        $serviceLocator
    );
};

$acceptorMiddleware = new AcceptorMiddleware($container);

$acceptor = Acceptor::create($acceptorMiddleware, MappedListeners::create());

$dispatcherMiddleware = $pimple['dispatcher_middleware'];
$dispatcher = Dispatcher::create($dispatcherMiddleware);

$emitter = new \Bounce\Bounce\Emitter($acceptor, $dispatcher);

$emitter->addListener('foo', function($event) { var_dump($event); });

$emitter->emit('foo');