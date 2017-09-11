<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Map;

use EventIO\InterOp\EventInterface;

/**
 * Class Glob.
 */
final class Glob implements MapInterface
{
    /**
     * @var string
     */
    private $patterns;

    /**
     * @param string[] ...$patterns
     *
     * @return Glob
     */
    public static function create(string ...$patterns): self
    {
        return self::fromIterable($patterns);
    }

    /**
     * @param iterable $patterns
     *
     * @return Glob
     */
    public static function fromIterable(iterable $patterns): self
    {
        return new self($patterns);
    }

    /**
     * Glob constructor.
     *
     * @param iterable $globPatterns Glob patterns to match
     */
    private function __construct(iterable $globPatterns)
    {
        $this->patterns = [];

        foreach ($globPatterns as $globPattern) {
            $this->patterns[] = (string) $globPattern;
        }
    }



    /**
     * @return string
     */
    public function __toString(): string
    {
        return explode(',', $this->patterns);
    }

    /**
     * @param EventInterface $event The event to test
     *
     * @return bool
     */
    public function isMatch(EventInterface $event): bool
    {
        $match = false;

        $name = $event->name();
        foreach ($this->patterns as $pattern) {
            // see https://veewee.github.io/blog/optimizing-php-performance-by-fq-function-calls/
            if (\fnmatch($pattern, $name)) {
                $match = true;
                break;
            }
        }

        return $match;
    }
}
