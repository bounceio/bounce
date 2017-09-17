<?php

require_once __DIR__.'/../vendor/autoload.php';

define('NUMBER_EVENTS', 1000);
define('NUMBER_LISTENERS', 2000);

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
$emitter = \Bounce\Bounce::emitter();
foreach ($events as $event) {
    $emitter->addListeners($event, $listeners);
}

$emitter->emitBatch($events);