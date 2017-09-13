<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener\Collection;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use EventIO\InterOp\EventInterface;
use Traversable;

/**
 * Interface MappedListenerCollectionInterface.
 */
interface MappedListenerCollectionInterface
{
    /**
     * @param $originalListener
     * @param $newListener
     *
     * @return MappedListenerCollectionInterface
     */
    public function replaceListener(
        $originalListener,
        $newListener
    ): MappedListenerCollectionInterface;

    /**
     * @param MappedListenerInterface[] ...$mappedListener
     * @return mixed
     */
    public function add(MappedListenerInterface ...$mappedListener);

    /**
     * @param EventInterface $event
     *
     * @return mixed
     */
    public function listenersFor(EventInterface $event): Traversable;
}
