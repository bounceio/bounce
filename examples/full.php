<?php

use Bounce\Bounce;
use Bounce\Bounce\Event\Named;
use Bounce\Cartographer\Map\EventType;
use EventIO\InterOp\EventInterface;
use Symfony\Component\Dotenv\Dotenv;


require_once __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

define('NUMBER_EVENTS', getenv('BOUNCE_EVENTS'));
define('NUMBER_LISTENERS', getenv('BOUNCE_LISTENERS'));

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

$events = ['foo.bar', 'bar.foo', 'foo.baz', 'bal', 'bom'];
$emitter = Bounce::emitter();
$emitter->addListeners(new EventType(Named::class), $listeners($counter));

$eventRounds = ceil(NUMBER_EVENTS / count($events));

for ($i=0; $i<$eventRounds; $i++) {
    $emitter->emitBatch($events);
}
