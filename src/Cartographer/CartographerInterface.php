<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Cartographer;

use Bounce\Bounce\Map\MapInterface;

/**
 * Interface CartographerInterface
 * @package Bounce\Bounce\Cartography
 */
interface CartographerInterface
{
    /**
     * @param string $type
     * @param array  ...$eventMap
     *
     * @return MapInterface
     */
    public function map(string $type, ...$eventMap): MapInterface;
}
