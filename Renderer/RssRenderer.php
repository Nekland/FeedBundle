<?php

namespace Nekland\Bundle\FeedBundle\Renderer;

use Symfony\Component\Routing\Router;

use Nekland\Bundle\FeedBundle\XML\XMLManager;
use Nekland\Bundle\FeedBundle\Feed;
use Nekland\Bundle\FeedBundle\Item\ItemInterface;
use Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class render an xml file
 * Using format RSS 2.0
 *
 * @author Nek' <nek.dev+github@gmail.com>
 * @author Yohan Giarelli <yohan@giarelli.org>
 */

class RssRenderer implements RendererInterface
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $basePath;

    public function __construct(Router $router, $basePath)
    {
        $this->router = $router;
        $this->basePath = $basePath;
    }

    /**
     * Renders the feed
     *
     * @param \Nekland\Bundle\FeedBundle\Feed $feed
     * @return void
     */
    public function render(Feed $feed)
    {
        $filename = sprintf('%s/%s', $this->basePath, $feed->getFilename('rss'));
        if (is_file($filename)) {
            unlink($filename);
        }

        $xml = new XMLManager($filename);
        $this->init($xml, $feed);
        $this->writeItems($xml, $feed);
        $xml->save();
    }

    /**
     * Build the feed properties
     *
     * @param \Nekland\Bundle\FeedBundle\XML\XMLManager $xml
     * @param \Nekland\Bundle\FeedBundle\Feed           $feed
     * @return void
     */
    private function init(XMLManager $xml, Feed $feed)
    {
        $root = $xml->getXml()->createElement('rss');
        $root->setAttribute('version', '2.0');
        $root = $xml->getXml()->appendChild($root);

        $channel = $xml->getXml()->createElement('channel');
        $channel = $root->appendChild($channel);

        $xml->addTextNode('description', $feed->get('description'), $channel);

        $xml->addTextNode('pubDate', $feed->get('pubDate', new \DateTime())->format(\DateTime::RSS), $channel);
        $date = new \DateTime();
        $xml->addTextNode('lastBuildDate', $date->format(\DateTime::RSS), $channel);

        $xml->addTextNode('link', $feed->get('url'), $channel);
        $xml->addTextNode('title', $feed->get('title'), $channel);
        $xml->addTextNode('language', $feed->get('language'), $channel);

        if (null !== $feed->get('copyright')) {
            $xml->addTextNode('copyright', $feed->get('copyright'), $channel);
        }

        if (null !== $feed->get('managingEditor')) {
            $xml->addTextNode('managingEditor', $feed->get('managingEditor'), $channel);
        }

        if (null !== $feed->get('generator')) {
            $xml->addTextNode('generator', $feed->get('generator'), $channel);
        }

        if (null !== $feed->get('webMaster')) {
            $xml->addTextNode('webMaster', $feed->get('webMaster'), $channel);
        }

        $image = $feed->get('image', array());
        if (isset($image['url']) && isset($image['title']) && isset($image['link'])) {

            $imageNode = $xml->getXml()->createElement('image');
            $imageNode = $channel->appendChild($imageNode);
            $xml->addTextNode('url', $image['url'], $imageNode);
            $xml->addTextNode('title', $image['url'], $imageNode);
            $xml->addTextNode('link', $image['url'], $imageNode);


            if (isset($image['height'])) {
                $xml->addTextNode('height', $image['height'], $imageNode);
            }
            if (isset($image['width'])) {
                $xml->addTextNode('width', $image['width'], $imageNode);
            }
        }

        if (null !== $feed->get('ttl')) {
            $xml->addTextNode('ttl', $feed->get('ttl'), $channel);
        }

    }

    /**
     * Write Feed Items
     *
     * @param \Nekland\Bundle\FeedBundle\XML\XMLManager $xml
     * @param \Nekland\Bundle\FeedBundle\Feed           $feed
     * @return void
     */
    private function writeItems(XMLManager $xml, Feed $feed)
    {
        foreach ($feed as $item) {
            $this->writeItem($xml, $item);
        }
    }

    /**
     * Write an ItemInterface into the feed
     *
     * @param \Nekland\Bundle\FeedBundle\XML\XMLManager     $xml
     * @param \Nekland\Bundle\FeedBundle\Item\ItemInterface $item
     * @return void
     */
    private function writeItem(XMLManager $xml, ItemInterface $item)
    {
        $nodeItem = $this->createItem($xml);
        $xml->addTextNode('title', $item->getFeedTitle(), $nodeItem);
        $xml->addTextNode('description', $item->getFeedDescription(), $nodeItem);

        $id=$item->getFeedId();
        if(empty($id))
            throw new \InvalidArgumentException('The method « getFeedId » MUST return a not empty value.');
        $xml->addTextNode('guid', $id, $nodeItem);

        $xml->addTextNode('link', $this->getRoute($item), $nodeItem);

        $xml->addTextNode('pubDate', $item->getFeedDate()->format(\DateTime::RSS), $nodeItem);

        if ($this->itemHas($item, 'getFeedAuthor')) {
            if ($author = $this->getAuthor($item)) {
                $xml->addTextNode('author', $author, $nodeItem);
            }
        }
        if ($this->itemHas($item, 'getFeedCategory')) {
            $xml->addTextNode('category', $item->getFeedCategory(), $nodeItem);
        }
        if ($this->itemHas($item, 'getFeedCommentRoute')) {
            if ($comments = $this->getComments($item)) {
                $xml->addTextNode('comments', $comments, $nodeItem);
            }
        }
        if ($this->itemHas($item, 'getFeedEnclosure')) {
            if ($enclosure = $this->getEnclosure($item, $xml)) {
                $nodeItem->appendChild($enclosure);
            }
        }
    }

    /**
     * Create an Item node
     *
     * @param \Nekland\Bundle\FeedBundle\XML\XMLManager $xml
     * @return \DOMElement
     */
    private function createItem(XMLManager $xml)
    {
        $itemNode = $xml->getXml()->createElement('item');
        $channelNode = $xml->getXml()->getElementsByTagName('channel')->item(0);
        $channelNode->appendChild($itemNode);

        return $itemNode;
    }

    /**
     * Extract the author email
     *
     * @param \Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface $item
     * @return string|null
     */
    private function getAuthor(ItemInterface $item)
    {
        $authorData = $item->getFeedAuthor();
        $author = '';
        if(isset($authorData['name'])) {
            $author .= $authorData['name'];
        }
        if (isset($authorData['email'])) {
            $author .= empty($author) ? $authorData['email'] : ' ' . $authorData['email'];
        }

        if(!empty($author)) {
            return $author;
        }

        return null;
    }

    /**
     * Extracts the Comments URI
     *
     * @param \Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface $item
     * @return null|string
     */
    private function getComments(ItemInterface $item)
    {
        $commentRoute = $item->getFeedCommentRoute();
        if (!$commentRoute) {
            return null;
        } elseif (is_array($commentRoute)) {
            return $this->router->generate($commentRoute[0], $commentRoute[1]);
        } else {
            return $this->router->generate($commentRoute);
        }
    }

    /**
     * Extract enclosure
     *
     * @param \Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface $item
     * @param \Nekland\Bundle\FeedBundle\XML\XMLManager             $xml
     * @return \DOMElement|null
     */
    private function getEnclosure(ExtendedItemInterface $item, XMLManager $xml)
    {
        $enc = $item->getFeedEnclosure();
        if (is_array($enc)) {
            $enclosure = $xml->getXml()->createElement('enclosure');
            foreach ($enc as $key => $value) {
                $enclosure->setAttribute($key, $value);
            }

            return $enclosure;
        }

        return null;
    }

    private function getRoute(ItemInterface $item) {
        $route = $item->getFeedRoutes();
        //var_dump($item); exit();
        //var_dump($route); exit();
        if(!isset($route[0]) || !is_array($route[0])) {
            throw new \InvalidArgumentException('The « getFeedRoutes » method have to return an array of routes.');
        }
        $route = $route[0];
        if(!empty($route['route']) && is_array($route['route'])) {

            return $this->router->generate($route['route'][0], $route['route'][1], true);
        } else if(!empty($route['url'])) {

            return $route['url'];
        } else {
            return null;
        }
    }

    private function itemHas(ItemInterface $item, $method) {
        $rc = new \ReflectionClass($item);
        if($rc->hasMethod($method)) {
            return true;
        } else {
            return false;
        }
    }

}
