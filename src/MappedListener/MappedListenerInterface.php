<?php
namespace Bounce\Bounce\MappedListener;

use EventIO\InterOp\ListenerInterface;
use Shrikeh\Bounce\Event\Map\MapInterface;

/**
 * Interface MappedListenerInterface
 * @package Bounce\Bounce
 */
interface MappedListenerInterface
{
    public function map(): MapInterface;

    public function listener(): ListenerInterface;
}