<?php

namespace Nekland\Bundle\FeedBundle\Loader;

use Nekland\Bundle\FeedBundle\Feed;
use Nekland\Bundle\FeedBundle\Item\GenericItem;

/**
 * Loads Atom-XML and build a Feed object
 *
 * @throws \InvalidArgumentException
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev+github@gmail.com>
 */
class AtomLoader implements LoaderInterface
{
    protected static $methodMapping = array(
        'published' 	=> 'setFeedDate',
        'contributor' 	=> 'setAtomContributors',
        'content'		=> 'setFeedDescription',
        'link'          => 'setFeedRoutes'
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


        foreach ($xml->children() as $xmlTag) {
            if ($xmlTag->getName() != 'entry') {
                $this->setParam($xmlTag, $feed);
            } else {
                $this->addItem($xmlTag, $feed);
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
                    'setFeed' . ucfirst($subElement->getName());
            $data = '';


            if ($subElement->getName() == 'link') {

                if(($routes = $item->getFeedRoutes()) == null)
                    $routes = array();

                $i = count($routes);

                foreach($subElement->attributes() as $attrName => $attrValue) {
                    if($attrName == 'href') {
                        $routes[$i]['url'] = $attrValue;
                    } else {
                        $routes[$i][$attrName] = $attrValue;
                    }
                }
                $data = $routes;

            } elseif ($subElement->getName() == 'published') {
                $data = \DateTime::createFromFormat(\DateTime::ATOM, (string) $subElement);

            } elseif (count($subElement) === 0 && $subElement->getName() != 'updated') {

                if($subElement->getName() == 'content' || $subElement->getName() == 'title' || $subElement->getName() == 'summary') {
                    $typemethod = 'setAtom' . $subElement->getName() . 'Type';

                    $attributes = $subElement->attributes();
                    if(isset($attributes['type']))
                        $item->$typemethod($attributes['type']);
                    if(isset($attributes['xml:lang']) && $subElement->getName() == 'content') {
                        $item->setAtomContentLanguage($attributes['xml:lang']);
                    }
                }
                $data = (string) $subElement;

            } else {
                $data = $this->extractParam($subElement);
            }
            $reflection = new \ReflectionClass(get_class($item));
            if($reflection->hasMethod($method))
                $item->$method($data);
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
     * @return string format
     */
    public function getFormat()
    {
        return 'atom';
    }


}
