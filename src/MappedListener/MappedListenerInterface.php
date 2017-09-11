<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener;

use Bounce\Bounce\Map\MapInterface;
use EventIO\InterOp\ListenerInterface;

/**
 * Interface MappedListenerInterface.
 */
interface MappedListenerInterface
{
    /**
     * @return MapInterface
     */
    public function map(): MapInterface;

    /**
     * @param $event
     *
     * @return bool
     */
    public function matches($event): bool;

    /**
     * @return callable|ListenerInterface
     */
    public function listener();

    /**
     * @return mixed
     */
    public function priority();
}
