<?php

use Bounce\Bounce;
use EventIO\InterOp\EventInterface;

require_once __DIR__.'/../vendor/autoload.php';

define('NUMBER_EVENTS', 1000);
define('NUMBER_LISTENERS', 1000);

$counter = new StdClass;

$counter->count = 0;

$listeners = function($counter) {
    for ($i=0;$i<NUMBER_LISTENERS; $i++) {
        yield function (EventInterface $event) use ($counter) {
            $msg = "%d: %s\n";
            echo sprintf($msg, $counter->count, $event->name());
            $counter->count++;
        };
    }
};

$events = ['foo', 'bar', 'baz', 'bal', 'bom'];
$emitter = Bounce::emitter();
$emitter->addListeners('*', $listeners($counter));

$eventRounds = ceil(NUMBER_EVENTS / count($events));

for ($i=0; $i<$eventRounds; $i++) {
    $emitter->emitBatch($events);
}


