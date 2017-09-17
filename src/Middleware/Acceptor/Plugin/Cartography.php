<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\CartographerInterface;
use Ds\Map;
use stdClass;

/**
 * Class Cartography
 * @package Bounce\Bounce\Middleware\Acceptor\Plugin
 */
class Cartography implements AcceptorPluginInterface
{
    /**
     * @var CartographerInterface
     */
    private $cartographer;

    /**
     * @var Map
     */
    private $maps;

    /**
     * @param CartographerInterface|null $cartographer
     *
     * @return Cartography
     */
    public static function create(CartographerInterface $cartographer = null)
    {
        if (null == $cartographer) {
            $cartographer = new Cartographer();
        }

        return new self($cartographer);
    }

    /**
     * Cartography constructor.
     * @param CartographerInterface $cartographer
     */
    private function __construct(CartographerInterface $cartographer)
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
        if (!$this->maps->hasKey($map)) {
            $this->maps->put($map, $this->createMap($map));
        }

        return $this->maps->get($map);
    }

    /**
     * @param $map
     * @return \Bounce\Bounce\Map\MapInterface
     */
    private function createMap($map)
    {
        return $this->cartographer->map(
            Cartographer::MAP_GLOB,
            $map
        );
    }
}
