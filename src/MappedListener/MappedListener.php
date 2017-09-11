<?php
/**
 * @author       Barney Hanlon <barney@shrikeh.net>
 * @copyright    Barney Hanlon 2017
 * @license      https://opensource.org/licenses/MIT
 */

namespace Bounce\Bounce\MappedListener;

use Bounce\Bounce\Map\MapInterface;
use EventIO\InterOp\ListenerInterface;
use StdClass;

/**
 * Interface MappedListenerInterface.
 */
class MappedListener implements MappedListenerInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    /**
     * @var callable|ListenerInterface
     */
    private $listener;


    /**
     * @var mixed
     */
    private $priority;

    /**
     * @param StdClass $dto
     *
     * @return MappedListener
     */
    public static function fromDto(StdClass $dto): self
    {
        return new self(
            $dto->map,
            $dto->listener,
            $dto->priority
        );
    }

    /**
     * MappedListener constructor.
     *
     * @param MapInterface $map
     * @param              $listener
     * @param              $priority
     *
     * @throws \Exception
     */
    private function __construct(
        MapInterface $map,
        $listener,
        $priority
    ) {
        if (!(is_callable($listener) || ($listener instanceof ListenerInterface))) {
            throw new \Exception('foo');
        }

        $this->map      = $map;
        $this->listener = $listener;
        $this->priority = $priority;
    }

    /**
     * @return MapInterface
     */
    public function map(): MapInterface
    {
        return $this->map;
    }

    /**
     * @param $event
     *
     * @return bool
     */
    public function matches($event): bool
    {
        return $this->map()->isMatch($event);
    }

    /**
     * @return callable|ListenerInterface
     */
    public function listener()
    {
        return $this->listener;
    }

    /**
     * @return mixed
     */
    public function priority()
    {
        return $this->priority;
    }
}
