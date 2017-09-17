<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Dispatcher\Dispatcher;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\Middleware\Acceptor\ContainerMiddleware as AcceptorMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\ContainerMiddleware as DispatcherMiddleware;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\CallableListeners;
use Bounce\Bounce\Middleware\Dispatcher\Plugin\NamedEvent;
use Bounce\Bounce\ServiceProvider\Middleware\AcceptorServiceProvider;
use Bounce\Bounce\ServiceProvider\Middleware\DispatcherServiceProvider;
use Pimple\Container;

require_once __DIR__.'/../vendor/autoload.php';

$pimple = new Container();

$pimple->register(new AcceptorServiceProvider());
$pimple->register(new DispatcherServiceProvider());


$acceptor   = Acceptor::create(
    $pimple['bounce.middleware.acceptor'],
    MappedListeners::create()
);

$dispatcher = Dispatcher::create($pimple['bounce.middleware.dispatcher']);

$emitter = new \Bounce\Bounce\Emitter($acceptor, $dispatcher);

$emitter->addListener('*', function($event) use ($emitter) {
    var_dump($event);
    $emitter->emit('bar');
});

$emitter->addListener('*', function($event) use ($emitter) {
    var_dump('here', $event);
    if ($event->name() == 'bar') {
        die('here');
    }
});

$emitter->emit('foo');