<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\MappedListener\MappedListener;
use Bounce\Bounce\MappedListener\MappedListenerInterface;
use stdClass;

/**
 * Class ListenerMapper
 */
class ListenerMapper implements AcceptorPluginInterface
{
    /**
     * @param object|stdClass $parts
     * @param callable        $next
     *
     * @return mixed
     */
    public function __invoke($parts, callable $next)
    {
        $parts = $next($parts);
        if (!$parts instanceof MappedListenerInterface) {
            $parts = MappedListener::fromDto($parts);
        }

        return $parts;
    }
}
