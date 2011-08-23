<?php

namespace Nekland\FeedBundle\Renderer;

use Symfony\Component\Routing\Router;

use Nekland\FeedBundle\XML\XMLManager;
use Nekland\FeedBundle\Feed;
use Nekland\FeedBundle\Item\ItemInterface;
use Nekland\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class render an xml file
 * Using format RSS 2.0
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

    private function init(XMLManager $xml, Feed $feed)
    {
        $root = $xml->getXml()->createElement('rss');
        $root->setAttribute('version', '2.0');
        $root = $xml->getXml()->appendChild($root);

        $channel = $xml->getXml()->createElement('channel');
        $channel = $root->appendChild($channel);

        $xml->addTextNode('description', $feed->get('description'), $channel);
        $xml->addTextNode('pubDate', date('D, j M Y H:i:s e'), $channel);
        $xml->addTextNode('lastBuildDate', date('D, j M Y H:i:s e'), $channel);
        $xml->addTextNode('link', $this->router->generate(
            $feed->get('route'),
            $feed->get('route_parameters', array()),
            true
        ), $channel);
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

    private function writeItems(XMLManager $xml, Feed $feed)
    {
        foreach ($feed as $item) {
            $this->writeItem($xml, $item);
        }
    }

    private function writeItem(XMLManager $xml, ItemInterface $item)
    {
        $nodeItem = $this->createItem($xml);
        $xml->addTextNode('title', $item->getTitle(), $nodeItem);
        $xml->addTextNode('description', $item->getDescription(), $nodeItem);

        $route = $item->getRoute();

        $xml->addTextNode('guid', $item->getFeedId(), $nodeItem);

        if (is_array($route)) {
            $xml->addTextNode('description', $this->router->generate($route[0], $route[1]), $nodeItem);
        } else {
            $xml->addTextNode('description', $this->router->generate($route), $nodeItem);
        }

        $xml->addTextNode('pubDate', date('D, j M Y H:i:s e'), $nodeItem);

        if ($item instanceof ExtendedItemInterface) {

            $xml->addTextNode('author', $item->getAuthor(), $nodeItem);
            $xml->addTextNode('category', $item->getCategory(), $nodeItem);

            if ($comments = $this->getComments($item)) {
                $xml->addTextNode('comments', $comments, $nodeItem);
            }

            if ($enclosure = $this->getEnclosure($item, $xml)) {
                $nodeItem->appendChild($enclosure);
            }
        }
    }

    private function createItem(XMLManager $xml)
    {
        $itemNode = $xml->getXml()->createElement('item');
        $channelNode = $xml->getXml()->getElementsByTagName('channel')->item(0);
        $channelNode->appendChild($itemNode);

        return $itemNode;
    }

    private function getComments(ExtendedItemInterface $item)
    {
        $commentRoute = $item->getCommentRoute();
        if (!$commentRoute) {
            return null;
        } else if (is_array($commentRoute)) {
            return $this->router->generate($commentRoute[0], $commentRoute[1]);
        } else {
            return $this->router->generate($commentRoute);
        }
    }

    private function getEnclosure(ExtendedItemInterface $item, XMLManager $xml)
    {
        $enc = $item->getEnclosure();
        if (is_array($enc)) {
            $enclosure = $xml->getXml()->createElement('enclosure');
            foreach ($enc as $key => $value) {
                $enclosure->setAttribute($key, $value);
            }

            return $enclosure;
        }

        return null;
    }

}
