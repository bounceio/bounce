<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Map;

use Ds\Set;
use EventIO\InterOp\EventInterface;

/**
 * Class Glob.
 */
final class Name implements MapInterface
{
    /**
     * @var \Ds\Set
     */
    private $names;

    public function __construct(string ...$names)
    {
        $this->names = new Set($names);
    }
    /**
     * @param EventInterface $event
     *
     * @return bool
     */
    public function isMatch(EventInterface $event): bool
    {
        return $this->names->contains($event->name());
    }
}
