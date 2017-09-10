<?php
namespace Features\Bounce\Bounce;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Bounce\Bounce\ServiceProvider\Bounce;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var PsrContainer
     */
    private $container;

    private $events;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $container = new Container();
        $container->register(new Bounce());
        $this->container = new PsrContainer($container);
    }

    /**
     * @Given that I have the following events I want to emit:
     */
    public function thatIHaveTheFollowingEventsIWantToEmit(TableNode $table)
    {
        foreach ($table as $row) {
            $this->events[] = $row['Name'];
        }
    }

    /**
     * @When I emit the events
     */
    public function iEmitTheEvents()
    {
        $this->container->get(Bounce::EMITTER)->emitBatch($this->events);
    }

    /**
     * @Then a listener for each event receives them.
     */
    public function aListenerForEachEventReceivesThem()
    {
        throw new PendingException();
    }
}
