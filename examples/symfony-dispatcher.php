<?php

use Bounce\Bounce\Acceptor\Acceptor;
use Symfony\Component\EventDispatcher\EventDispatcher;

ini_set('memory_limit', '256m');
require_once __DIR__.'/../vendor/autoload.php';

$dispatcher = new EventDispatcher();

$eventName = 'foo';

$priorities = [
    Acceptor::PRIORITY_LOW,
    Acceptor::PRIORITY_NORMAL,
    Acceptor::PRIORITY_HIGH,
    Acceptor::PRIORITY_URGENT,
    Acceptor::PRIORITY_CRITICAL
];

$listeners = function($priorities) {
    for ($i=0; $i<1000; $i++) {
        foreach(['foo', 'bar', 'baz'] as $str) {
            $priority = array_rand($priorities);

            yield $priority => function($event) use($i, $str, $priority) {
                echo "$i: $priority : $str\n";
            };
        }
    }
};

foreach ($listeners($priorities) as $priority => $listener) {
    $dispatcher->addListener($eventName, $listener, $priority);
}

$start = microtime(true);
for ($i=0; $i<10000; $i++) {

    $dispatcher->dispatch($eventName);

}
$end = microtime(true);
echo $end - $start;
