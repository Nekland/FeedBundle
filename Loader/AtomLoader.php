<?php

namespace Nekland\FeedBundle\Loader;

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
        'updated' => 'setFeedDate'
    );
    
    /**
     * @throws \InvalidArgumentException
     * @param $feedContent
     * @return \Nekland\FeedBundle\Feed
     */
    public function load($feedContent)
    {
        $feed = new Feed(array('class' => 'Nekland\\FeedBundle\\Item\\GenericItem'));
        $xml = simplexml_load_string($feedContent);

        if (false === $xml) {
            throw new \InvalidArgumentException('The given data is not a valid XML string.');
        }


        foreach ($xml->feed[0] as $xmlEntry) {
            if ($xmlEntry->getName() != 'entry') {
                $this->setParam($xmlEntry, $feed);
            } else {
                $this->addItem($xmlEntry, $feed);
            }
        }

        return $feed;
    }

    /**
     * Adds an Item to the feed
     *
     * @param \SimpleXMLElement $element
     * @param \Nekland\FeedBundle\Feed $feed
     * @return void
     */
    protected function addItem(\SimpleXMLElement $element, Feed $feed)
    {
        $item = new GenericItem;
        foreach ($element as $subElement) {
            $method = isset(self::$methodMapping[$subElement->getName()]) ?
                    self::$methodMapping[$subElement->getName()] :
                    'setFeed' . ucfirst($subElement->getName());

            if ($subElement->getName() == 'author') {
                $author = array();
                foreach($subElement as $authorElement) {
                    $author[$authorElement->getName()] = (string) $authorElement;
                }
                $item->setAuthor($author);
            } else if (count($subElement) === 0) {
                $item->$method((string)$subElement);
            } else {
                $item->$method($this->extractParam($subElement));
            }
        }

        $feed->add($item);
    }

    /**
     * @return string format
     */
    public function getFormat()
    {
        return 'atom';
    }


}