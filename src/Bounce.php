<?php
namespace Bounce;

use EventIO\InterOp\EmitterInterface;
use Pimple\Container;
use Bounce\Bounce\ServiceProvider\Bounce as BounceServiceProvider;
use Psr\Container\ContainerInterface;

class Bounce
{
    public static function container(): ContainerInterface
    {
        $pimple = new Container();
        $pimple->register(new BounceServiceProvider);
        return new \Pimple\Psr11\Container($pimple);
    }

    public static function emitter(): EmitterInterface
    {
        $container = self::container();

        return $container->get(BounceServiceProvider::EMITTER);
    }
}