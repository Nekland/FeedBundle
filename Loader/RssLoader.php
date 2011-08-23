<?php

namespace Nekland\FeedBundle\Loader;

use Nekland\FeedBundle\Feed;
use Nekland\FeedBundle\Item\GenericItem;

class RssLoader implements LoaderInterface
{
    protected static $methodMapping = array(
        'guid'    => 'setFeedId',
        'pubDate' => 'setDate'
    );

    /**
     * @throws \InvalidArgumentException
     * @param $feedContent
     * @return \Nekland\FeedBundle\Feed
     */
    public function load($feedContent)
    {
        $xml = simplexml_load_string($feedContent);
        if (false === $xml) {
            throw new \InvalidArgumentException('The given data is not a valid XML string.');
        }

        $feed = new Feed(array('class' => 'Nekland\\FeedBundle\\Item\\GenericItem'));

        foreach ($xml->channel[0] as $xmlItem) {
            if ($xmlItem->getName() != 'item') {
                $this->setParam($xmlItem, $feed);
            } else {
                $this->addItem($xmlItem, $feed);
            }
        }

        return $feed;
    }

    protected function addItem(\SimpleXMLElement $element, Feed $feed)
    {
        $item = new GenericItem;
        foreach ($element as $subElement) {
            $method = isset(self::$methodMapping[$subElement->getName()]) ?
                self::$methodMapping[$subElement->getName()] :
                'set'.ucfirst($subElement->getName());

            if ($subElement->getName() == 'author') {
                $item->setAuthor(array('email' => (string)$subElement));
            } else if (count($subElement) === 0) {
                $item->$method((string)$subElement);
            } else {
                $item->$method($this->extractParam($subElement));
            }
        }

        $feed->add($item);
    }

    protected function setParam(\SimpleXMLElement $element, Feed $feed)
    {
        if (count($element) === 0) {
            $feed->set($element->getName(), (string)$element);
        } else {
            $feed->set($element->getName(), $this->extractParam($element));
        }
    }

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