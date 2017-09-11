<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

/**
 * Interface AcceptorPluginInterface
 * @package Bounce\Bounce\Middleware\Acceptor\Plugin
 */
interface AcceptorPluginInterface
{

    /**
     * @param iterable $parts
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(iterable $parts, callable $next);
}
