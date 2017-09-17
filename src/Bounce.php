<?php
namespace Bounce;

use Pimple\Container;
use Bounce\Bounce\ServiceProvider\Bounce as BounceServiceProvider;

class Bounce
{
    public static function container()
    {
        $pimple = new Container();
        $pimple->register(new BounceServiceProvider);
        return new \Pimple\Psr11\Container($pimple);
    }

    public static function emitter()
    {
        $container = self::container();

        return $container->get(BounceServiceProvider::EMITTER);
    }
}