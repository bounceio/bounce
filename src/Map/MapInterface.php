<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Shrikeh\Bounce\Event\Map;

use EventIO\InterOp\EventInterface;

/**
 * Interface MapInterface.
 */
interface MapInterface
{
    public function isMatch(EventInterface $event): bool;

    /**
     * @return string
     */
    public function index(): string;
}
