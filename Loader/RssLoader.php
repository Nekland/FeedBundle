<?php

namespace Nekland\Bundle\FeedBundle\Loader;

use Nekland\Bundle\FeedBundle\Feed;
use Nekland\Bundle\FeedBundle\Item\GenericItem;

/**
 * Loads RSS-XML and build a Feed object
 *
 * @throws \InvalidArgumentException
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev+github@gmail.com>
 */
class RssLoader implements LoaderInterface
{
    protected static $methodMapping = array(
        'guid'    => 'setFeedId',
        'pubDate' => 'setFeedDate'

    );

    /**
     * @throws \InvalidArgumentException
     * @param $feedContent
     * @return \Nekland\Bundle\FeedBundle\Feed
     */
    public function load($feedContent)
    {
        $feed = new Feed(array('class' => 'Nekland\\Bundle\\FeedBundle\\Item\\GenericItem'));
        $xml = simplexml_load_string($feedContent);

        if (false === $xml) {
            throw new \InvalidArgumentException('The given data is not a valid XML string.');
        }



        foreach ($xml->channel[0] as $xmlItem) {
            if ($xmlItem->getName() != 'item') {
                $this->setParam($xmlItem, $feed);
            } else {
                $this->addItem($xmlItem, $feed);
            }
        }

        return $feed;
    }

    /**
     * Adds an Item to the feed
     *
     * @param \SimpleXMLElement        $element
     * @param \Nekland\Bundle\FeedBundle\Feed $feed
     * @return void
     */
    protected function addItem(\SimpleXMLElement $element, Feed $feed)
    {
        $item = new GenericItem;
        foreach ($element as $subElement) {
            $method = isset(self::$methodMapping[$subElement->getName()]) ?
                self::$methodMapping[$subElement->getName()] :
                'setFeed'.ucfirst($subElement->getName());

            if ($subElement->getName() == 'author') {
                $item->setFeedAuthor(array('name' => (string)$subElement));

            } else if($subElement->getName() == 'pubDate') {
                $date = \DateTime::createFromFormat(\DateTime::RSS, (string) $subElement);
                $item->setFeedDate($date);

            } else if($subElement->getName() == 'link') {
                $routes = array();
                $routes[0] = array('url' => (string) $subElement);
                $item->setFeedRoutes($routes);

            } elseif (count($subElement) === 0) {
                $item->$method((string)$subElement);

            } else {
                $item->$method($this->extractParam($subElement));
            }
        }

        $feed->add($item);
    }

    /**
     * Set a feed param
     *
     * @param \SimpleXMLElement        $element
     * @param \Nekland\Bundle\FeedBundle\Feed $feed
     * @return void
     */
    protected function setParam(\SimpleXMLElement $element, Feed $feed)
    {
        if (count($element) === 0) {
            $feed->set($element->getName(), (string)$element);
        } else {
            $feed->set($element->getName(), $this->extractParam($element));
        }
    }

    /**
     * Extract array params
     *
     * @param \SimpleXMLElement $element
     * @return array
     */
    protected function extractParam(\SimpleXMLElement $element)
    {
        $param = array();
        foreach ($element as $subElement) {
            if (count($subElement) === 0) {
                $param[$subElement->getName()] = (string)$subElement;
            } else {
                $param[$subElement->getName()] = $this->extractParam($subElement);
            }
        }

        return $param;
    }

    /**
     * @return string (ex: "rss")
     */
    public function getFormat()
    {
        return 'rss';
    }
}
