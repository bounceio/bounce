<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Bounce\Bounce\Dispatcher\Dispatcher;
use Bounce\Bounce\Emitter;
use Bounce\Bounce\MappedListener\Collection\MappedListeners;
use Bounce\Bounce\ServiceProvider\Middleware\AcceptorServiceProvider;
use Bounce\Bounce\ServiceProvider\Middleware\DispatcherServiceProvider;
use Pimple\Container;

require_once __DIR__.'/../vendor/autoload.php';

$pimple = new Container();

$pimple->register(new AcceptorServiceProvider());
$pimple->register(new DispatcherServiceProvider());


$acceptor   = Acceptor::create(
    $pimple[AcceptorServiceProvider::MIDDLEWARE],
    MappedListeners::create()
);

$dispatcher = Dispatcher::create($pimple[DispatcherServiceProvider::MIDDLEWARE]);

$emitter = new Emitter($acceptor, $dispatcher);

$listeners = [
    function() {
        echo 'here';
    },
    function() {
        echo 'here';
    },
    function() {
        echo 'here';
    },
    function() {
        echo 'here';
    },
    function() {
        echo 'here';
    },
    function() {
        echo 'here';
    },
];


$events = ['foo', 'bar', 'baz', 'bal', 'bom'];
foreach ($events as $event) {
    $emitter->addListeners($event, $listeners);
}

$emitter->emitBatch($events);