<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener;

use EventIO\InterOp\ListenerInterface;
use Shrikeh\Bounce\Event\Map\MapInterface;

/**
 * Interface MappedListenerInterface.
 */
interface MappedListenerInterface
{
    public function map(): MapInterface;

    public function listener(): ListenerInterface;
}
