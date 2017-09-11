<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use stdClass;


/**
 * Interface AcceptorPluginInterface
 * @package Bounce\Bounce\Middleware\Acceptor\Plugin
 */
interface AcceptorPluginInterface
{
    /**
     * @param object|stdClass $parts
     * @param callable        $next
     *
     * @return mixed
     */
    public function __invoke($parts, callable $next);
}
