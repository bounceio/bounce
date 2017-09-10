<?php
namespace Features\Bounce\Bounce;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given that I have the following events I want to emit:
     */
    public function thatIHaveTheFollowingEventsIWantToEmit(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @When I emit the events
     */
    public function iEmitTheEvents()
    {
        throw new PendingException();
    }

    /**
     * @Then a listener for each event receives them.
     */
    public function aListenerForEachEventReceivesThem()
    {
        throw new PendingException();
    }
}
