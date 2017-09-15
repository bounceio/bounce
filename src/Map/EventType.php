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
 * Class EventType.
 */
final class EventType implements MapInterface
{
    /**
     * @var \Ds\Set
     */
    private $eventTypes;

    /**
     * EventType constructor.
     * @param string[] ...$eventTypes
     */
    public function __construct(string ...$eventTypes)
    {
        $this->eventTypes = new Set($eventTypes);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return explode(',', $this->eventTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function isMatch(EventInterface $event): bool
    {
        $match = false;
        foreach ($this->eventTypes as $eventType) {
            // see https://veewee.github.io/blog/optimizing-php-performance-by-fq-function-calls/
            if (\is_a($event, $eventType)) {
                $match = true;
                break;
            }
        }

        return $match;
    }
}
