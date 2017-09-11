<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\CartographerInterface;
use stdClass;

class ListenerMap implements AcceptorPluginInterface
{
    /**
     * @var CartographerInterface
     */
    private $cartographer;

    /**
     * @param CartographerInterface|null $cartographer
     *
     * @return ListenerMap
     */
    public static function create(CartographerInterface $cartographer = null)
    {
        if (null == $cartographer) {
            $cartographer = new Cartographer();
        }

        return new self($cartographer);
    }

    private function __construct(CartographerInterface $cartographer)
    {
        $this->cartographer = $cartographer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(stdClass $parts, callable $next)
    {
        $parts->map = $this->cartographer->map(
            Cartographer::MAP_GLOB,
            $parts->map
        );
        return $next($parts);
    }
}
