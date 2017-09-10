<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Map;

use EventIO\InterOp\EventInterface;

/**
 * Interface MapInterface.
 */
interface MapInterface
{
    /**
     * @param EventInterface $event
     * @return bool
     */
    public function isMatch(EventInterface $event): bool;
}
