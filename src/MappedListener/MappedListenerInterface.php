<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener;

use Bounce\Bounce\Map\MapInterface;

/**
 * Interface MappedListenerInterface.
 */
interface MappedListenerInterface
{
    const HIGHER_PRIORITY = -1;
    const SAME_PRIORITY = 0;
    const LOWER_PRIORITY = 1;

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
     * @return mixed
     */
    public function listener();

    /**
     * @param MappedListenerInterface $mappedListener
     *
     * @return int
     */
    public function compare(MappedListenerInterface $mappedListener): int;

    /**
     * @return mixed
     */
    public function priority();
}
