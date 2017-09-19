<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Cartographer\CartographerInterface;
use Bounce\Cartographer\Map\MapInterface;
use Ds\Map;

/**
 * Class Cartography
 * @package Bounce\Bounce\Middleware\Acceptor\Plugin
 */
class Cartography implements AcceptorPluginInterface
{
    const MAP_GLOB          = 'glob';
    const MAP_EVENT_TYPE    = 'event_type';
    const MAP_NAMED         = 'named';

    /**
     * @var CartographerInterface
     */
    private $cartographer;

    /**
     * @var Map
     */
    private $maps;

    /**
     * Cartography constructor.
     * @param CartographerInterface $cartographer
     */
    public function __construct(CartographerInterface $cartographer)
    {
        $this->cartographer = $cartographer;
        $this->maps         = new Map();
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($parts, callable $next)
    {
        $parts->map = $this->chart($parts->map);

        return $next($parts);
    }

    /**
     * @param $map
     * @return mixed
     */
    public function chart($map)
    {
        if (!$map instanceof MapInterface) {
            $map = $this->fetchMap($map);
        }

        return $map;
    }

    /**
     * @param $map
     *
     * @return mixed
     */
    private function fetchMap($map)
    {
        if (!$this->maps->hasKey($map)) {
            $this->maps->put($map, $this->createMap($map));
        }

        return $this->maps->get($map);
    }

    /**
     * @param $map
     * @return \Bounce\Cartographer\Map\MapInterface
     */
    private function createMap($map)
    {
        return $this->cartographer->map(
            $this->mapType($map),
            $map
        );
    }

    /**
     * @param $map
     *
     * @return string
     */
    private function mapType($map): string
    {
        return self::MAP_GLOB;
    }
}
