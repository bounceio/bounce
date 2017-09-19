<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\ServiceProvider;

use Bounce\Bounce\Cartographer\Cartographer;
use Bounce\Bounce\Cartographer\Charts\GlobChart;
use Bounce\Bounce\Middleware\Acceptor\Plugin\Cartography;
use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;

class CartographerServiceProvider implements ServiceProviderInterface
{
    const CARTOGRAPHER      = 'bounce.cartographer';
    const SERVICE_LOCATOR   = self::CARTOGRAPHER.'.service_locator';
    const CHARTS            = self::CARTOGRAPHER.'.charts';
    const GLOB              = self::CHARTS.'.glob';

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple[self::GLOB] = function() {
            return new GlobChart();
        };

        $pimple[self::CHARTS] = function() {
            return [
                self::GLOB
            ];
        };

        $pimple[self::SERVICE_LOCATOR] = function(Container $con) {
            return new ServiceLocator(
                $con,
                $con[self::CHARTS]
            );
        };

        $pimple[self::CARTOGRAPHER] = function(Container $con) {
            return new Cartographer($con[self::SERVICE_LOCATOR]);
        };
    }
}
