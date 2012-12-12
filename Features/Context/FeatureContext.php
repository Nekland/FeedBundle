<?php

namespace Nekland\Bundle\FeedBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext,
    Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Nekland\Bundle\FeedBundle\Feed;


/**
 * Feature context.
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
class FeatureContext extends BehatContext
{
    /**
     * @var \Nekland\Bundle\FeedBundle\Factory\FeedFactory
     */
    protected $factory;

    /**
     * @var bool
     */
    protected $has;

    /**
     * @var Feed
     */
    protected $feed;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var \Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface
     */
    protected $currentItem;

    /**
     * @var \Nekland\Bundle\FeedBundle\Loader\RssLoader
     */
    protected $loader;

    /**
     * @var string
     */
    protected $xml;

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

    /**
     * @Given /^The feed is empty$/
     */
    public function theFeedIsEmpty()
    {
        $this->feed = new Feed(array('class' => 'Nekland\\Bundle\\FeedBundle\\Item\\GenericItem'));
    }

    /**
     * @When /^I get the item count$/
     */
    public function iGetTheItemCount()
    {
        $this->count = count($this->feed);
    }

    /**
     * @Then /^I got "([^"]*)"$/
     */
    public function iGot($argument1)
    {
        $this->assertEquals($argument1, $this->count);
    }

    /**
     * @When /^I add an Item$/
     */
    public function iAddAnItem($id = 0)
    {
        $item = new \Nekland\Bundle\FeedBundle\Item\GenericItem();
        $item->setFeedId($id);

        $this->feed->add($item);
    }

    /**
     * @Given /^I remove an Item$/
     */
    public function iRemoveAnItem()
    {
        unset($this->feed[1]);
    }


    /**
     * @Given /^I retrieve the Item "([^"]*)"$/
     */
    public function iRetrieveTheItem($argument1)
    {
        $this->currentItem = $this->feed[$argument1];
    }

    /**
     * @Given /^the item "([^"]*)" has the "([^"]*)" id$/
     */
    public function theItemHasTheId($argument1, $argument2)
    {
        $this->assertEquals($this->feed[$argument1]->getFeedId(), $argument2);
    }

    /**
     * @Given /^I replace this Item by an other$/
     */
    public function iReplaceThisItemByAnOther()
    {
        $item = new \Nekland\Bundle\FeedBundle\Item\GenericItem();
        $item->setFeedId(1);

        $this->feed->replace(0, $item);
    }

    /**
     * @When /^I set the "([^"]*)" param to "([^"]*)"$/
     */
    public function iSetTheParamTo($argument1, $argument2)
    {
        $this->feed->set($argument1, $argument2);
    }

    /**
     * @Given /^I add "([^"]*)" items$/
     */
    public function iAddItems($argument1)
    {
        for ($i = 0; $i < $argument1; $i++) {
            $this->iAddAnItem($i);
        }
    }

    /**
     * @Given /^I have this XML$/
     */
    public function iHaveThisXML(PyStringNode $string)
    {
        $this->xml = $string->getRaw();
    }

    /**
     * @When /^I load the string$/
     */
    public function iLoadTheString()
    {
        $this->loader = new \Nekland\Bundle\FeedBundle\Loader\RssLoader();
        $this->feed = $this->loader->load($this->xml);
    }

    /**
     * @Then /^the parameter "([^"]*)" is "([^"]*)"$/
     */
    public function theParameterIs($argument1, $argument2)
    {
        $this->assertEquals($this->feed->get($argument1), $argument2);
    }

    protected function assertEquals($value1, $value2)
    {
        if ($value1 != $value2) {
            throw new \Exception(sprintf(
                    'The value does not correspond to the expected one. (Got: %s, Expected: %s)',
                    $value2,
                    $value1
            ));
        }
    }
}
