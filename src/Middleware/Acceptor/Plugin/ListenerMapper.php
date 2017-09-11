<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\MappedListener\MappedListener;
use stdClass;

/**
 * Class ListenerMapper
 * @package Bounce\Bounce\Middleware\Acceptor\Plugin
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
        return $next(MappedListener::fromDto($parts));
    }
}
