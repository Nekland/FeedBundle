<?php

namespace Nekland\FeedBundle\Renderer;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Nekland\FeedBundle\XML\XMLManager;
use Nekland\FeedBundle\Feed;

/**
 * This class render an xml file
 * Using format RSS 2.0
 */

class RssRenderer implements RendererInterface
{
    protected $xmlManager;
    protected $items;
    protected $router;
    protected $basePath;

    public function __construct(Router $router, $basePath)
    {
        $this->router = $router;
        $this->basePath = $basePath;
    }

    public function render(Feed $feed)
    {
        
    }

    private function init()
    {
        $root = $this->xmlManager->getXml()->createElement('rss');
        $root->setAttribute('version', '2.0');
        $root = $this->xmlManager->getXml()->appendChild($root);

        $channel = $this->xmlManager->getXml()->createElement('channel');
        $channel = $root->appendChild($channel);

        $this->xmlManager->addTextNode('description', $this->config['description'], $channel);
        $this->xmlManager->addTextNode('pubDate', date('D, j M Y H:i:s e'), $channel);
        $this->xmlManager->addTextNode('lastBuildDate', date('D, j M Y H:i:s e'), $channel);
        $this->xmlManager->addTextNode('link', $this->router($this->config['route']), $channel);
        $this->xmlManager->addTextNode('title', $this->config['title'], $channel);
        $this->xmlManager->addTextNode('language', $this->config['language'], $channel);

        if (isset($this->config['copyright'])) {
            $this->xmlManager->addTextNode('copyright', $this->config['copyright'], $channel);
        }

        if (isset($this->config['managingEditor'])) {
            $this->xmlManager->addTextNode('managingEditor', $this->config['managingEditor'], $channel);
        }

        if (isset($this->config['generator'])) {
            $this->xmlManager->addTextNode('generator', $this->config['generator'], $channel);
        }

        if (isset($this->config['webMaster'])) {
            $this->xmlManager->addTextNode('webMaster', $this->config['webMaster'], $channel);
        }


        if (isset($this->config['image']) && isset($this->config['image']['url']) && isset($this->config['image']['title']) && isset($this->config['image']['link'])) {

            $image = $this->xmlManager->getXml()->createElement('image');
            $image = $channel->appendChild($image);
            $this->xmlManager->addTextNode('url', $this->config['image']['url'], $image);
            $this->xmlManager->addTextNode('title', $this->config['image']['title'], $image);
            $this->xmlManager->addTextNode('link', $this->config['image']['link'], $image);


            if (isset($this->config['image']['height'])) {

                $this->xmlManager->addTextNode('height', $this->config['image']['height'], $image);
            }
            if (isset($this->config['image']['width'])) {

                $this->xmlManager->addTextNode('width', $this->config['image']['width'], $image);
            }
        }

        if (isset($this->config['ttl'])) {
            $this->xmlManager->addTextNode('ttl', $this->config['ttl'], $channel);
        }

    }

    private function update()
    {

        // Setting the last publication (lastBuildDate)
        $lastBuild = $this->xmlManager->getXml()->getElementsByTagName('lastBuildDate')->item(0);
        $lastBuild->removeChild($lastBuild->firstChild);
        $text = $this->xmlManager->getXml()->createTextNode(date('D, j M Y H:i:s e'));
        $lastBuild->appendChild($text);

        // Deleting items if we have too much items
        $l = $this->xmlManager->getXml()->getElementsByTagName('item')->length;
        $nbDel = count($this->items) + $l - $this->config['max_items'];

        if ($nbDel > 0) {
            for ($i = 0; $i < $nbDel; $i++) {
                $this->xmlManager->getXml()->getElementsByTagName('channel')->item(0)
                        ->removeChild($this->xmlManager->getXml()->getElementsByTagName('item')->item($l - 1));
            }
        }
    }

    private function writeItems()
    {
        $ci = count($this->items);
        $rc = \ReflectionClass($this->config['class']);

        for ($i = 0; $i < $ci; $i++) {
            if (($itemNode = $this->exists($this->items[$i])) === false) {

                $this->writeItem($this->xmlManager->getXml()->createElement('item'), $this->items[$i]);
            } else {

                $this->updateItem($itemNode, $this->items[$i]);
            }

        }
    }

    private function writeItem(\DOMNode $nodeItem, RssItemInterface $item)
    {

        $this->xmlManager->addTextNode('title', $item->getRssTitle(), $item);
        $this->xmlManager->addTextNode('description', $item->getRssDescription(), $item);

        $route = $item->getRssRoute();
        if (is_array($route)) {

            $this->xmlManager->addTextNode('description', $this->router->generate($route[0], $route[1]), $item);
        } else {

            $this->xmlManager->addTextNode('description', $this->router->generate($route), $item);
        }
        $this->xmlManager->addTextNode('pubDate', date('D, j M Y H:i:s e'), $item);

        if ($rc->hasMethod('getRssAuthor')) {
            $this->xmlManager->addTextNode('author', $item->getRssAuthor(), $item);
        }

        if ($rc->hasMethod('getRssCategory')) {
            $this->xmlManager->addTextNode('category', $item->getRssCategory(), $item);
        }

        if ($rc->hasMethod('getRssCommentRoute')) {
            $commentRoute = $item->getRssCommentRoute();
            if (is_array($commentRoute)) {

                $this->xmlManager->addTextNode('comment', $this->router->generate($commentRoute[0], $commentRoute[1]), $item);
            } else {

                $this->xmlManager->addTextNode('comment', $this->router->generate($commentRoute), $item);
            }
        }
        if ($rc->hasMethod('getRssEnclosure')) {
            $enc = $item->getRssEnclosure();
            if (!is_array($enc)) {
                throw new \InvalidArgumentException('"getRssEnclosure" must return an array with properties.');
            }
            $enclosure = $this->xmlManager->getXml()->createElement('enclosure');
            foreach ($enc as $key => $value) {

                $enclosure->setAttribute($key, $value);
            }

            $item->appendChild($enclosure);
        }

        if ($rc->hasMethod('getRssId')) {
            $this->xmlManager->addTextNode('guid', $item->getRssGuid(), $item);
        }
    }

    private function updateItem(\DOMNode $nodeItem, RssItemInterface $item)
    {
        $l = $nodeItem->childNodes->length;
        for ($i = 0; $i < $l; $i++) {
            $nodeItem->removeChild($nodeItem->childNodes->item(0));
        }

        $this->writeItem($nodeItem, $item);

    }

    /**
     * Check if the item exists in the file
     */
    private function exists($item)
    {
        $l = $this->xmlManager->getXml()->getElementsByTagName('guid')->length;
        $itemNode = null;

        for ($i = 0; $i < $l || $id !== null; $i++) {
            $guidNode = $this->xmlManager->getXml()->getElementsByTagName('guid')->item($i);
            $id = $guidNode->childNodes->item(0)->wholeText;
            if ($id == $item->getId()) {
                $itemNode = $guidNode->parentNode;
            }
        }

        if ($id === null) {
            return false;
        } else {
            return $itemNode;
        }

    }
}
