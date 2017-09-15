<?php
require_once __DIR__.'/../vendor/autoload.php';

use Bounce\Bounce\Acceptor\Acceptor;

$priorities = [
    Acceptor::PRIORITY_LOW,
    Acceptor::PRIORITY_NORMAL,
    Acceptor::PRIORITY_HIGH,
    Acceptor::PRIORITY_URGENT,
    Acceptor::PRIORITY_CRITICAL
];

$counter = new stdClass();
$counter->count = 0;

for ($i=0; $i<5000000; $i++) {
    $priority = array_rand($priorities);

    foreach (['foo', 'bar', 'baz'] as $str) {
        $listener = function () use ($str, $priority, $counter) {
            $count = $counter->count;
            echo "$count: $priority : $str\n";
            $counter->count++;
        };
        $listener();
    }
}

var_dump($counter);
