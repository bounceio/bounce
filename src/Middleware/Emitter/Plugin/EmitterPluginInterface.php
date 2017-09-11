<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Emitter\Plugin;

/**
 * Interface EmitterPluginInterface
 */
interface EmitterPluginInterface
{
    /**
     * @param          $event
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke($event, callable $next);
}
