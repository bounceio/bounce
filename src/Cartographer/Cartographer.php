<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Cartographer;

use Bounce\Bounce\Map\Glob;
use Bounce\Bounce\Map\MapInterface;

class Cartographer implements CartographerInterface
{
    const MAP_GLOB = 'glob';

    /**
     * {@inheritdoc}
     */
    public function map(string $type, ...$eventMap): MapInterface
    {
        return Glob::fromIterable($eventMap);
    }
}
