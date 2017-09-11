<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\CartographerInterface;

class ListenerMap implements AcceptorPluginInterface
{
    /**
     * @var CartographerInterface
     */
    private $cartographer;

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
    public function __invoke(iterable $parts, callable $next)
    {
        list($mapString, $listener, $priority) = $parts;
        $mapString = $this->cartographer->map(
            Cartographer::MAP_GLOB,
            $mapString
        );
        return $next([$mapString, $listener, $priority]);
    }
}
