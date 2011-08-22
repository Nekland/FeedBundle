<?php

namespace Nekland\FeedBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext,
    Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends BehatContext
{
    protected $factory;

    protected $has;

    protected $feed;
    
    /**
     * @Given /^I know the container$/
     */
    public function iKnowTheContainer()
    {
        if (!$this->getContainer()) {
            throw new \Exception('No container');
        }
    }

    /**
     * @When /^I try get the service "([^"]*)"$/
     */
    public function iTryGetTheService($service)
    {
        $this->factory = $this->getContainer()->get($service);
    }

    /**
     * @Then /^I should get an instance of "([^"]*)"$/
     */
    public function iShouldGetAnInstanceOf($instance)
    {
        $rc = new \ReflectionClass($instance);
        if (!$rc->isInstance(false === strpos($instance, 'Factory') ? $this->feed : $this->factory)) {
            throw new \Exception('This is not the feed factory');
        }
    }

    /**
     * @Given /^The feed "([^"]*)" does not exists$/
     */
    public function theFeedDoesNotExists($argument1)
    {
    }

    /**
     * @When /^I call the has method for "([^"]*)"$/
     */
    public function iCallTheHasMethodFor($argument1)
    {
        $this->has = $this->getContainer()->get('nekland_feed.factory')->has($argument1);
    }

    /**
     * @Then /^The has method should return "([^"]*)"$/
     */
    public function theHasMethodShouldReturn($argument1)
    {
        if (!(bool)$argument1) {
            throw new Exception('Bad return from has');
        }
    }

    /**
     * @Given /^I add the "([^"]*)" feed$/
     */
    public function iAddTheFeed($argument1)
    {
        $this->getContainer()->get('nekland_feed.factory')->setConfig(array('feeds' => array(
            'bar' => array()
        )));
    }

    /**
     * @Given /^I try to get the feed "([^"]*)"$/
     */
    public function iTryToGetTheFeed($argument1)
    {
        $this->feed = $this->getContainer()->get('nekland_feed.factory')->get($argument1);
    }
}
