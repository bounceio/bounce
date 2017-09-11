<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */
namespace Bounce\Bounce\MappedListener\Queue;

use Bounce\Bounce\MappedListener\MappedListenerInterface;
use Traversable;

/**
 * Interface QueueInterface
 * @package Bounce\Bounce\MappedListener\Queue
 */
interface QueueInterface
{
    /**
     * @param MappedListenerInterface[] ...$mappedListeners
     * @return mixed
     */
    public function queue(MappedListenerInterface ...$mappedListeners);

    /**
     * @return Traversable
     */
    public function listeners(): Traversable;
}
