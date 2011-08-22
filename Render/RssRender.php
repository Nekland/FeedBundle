<?php

namespace Nekland\FeedBundle\Render;

use Nekland\FeedBundle\XML\XMLManager;
use Nekland\FeedBundle\Item\RssItemInterface;

//use Nekland\FeedBundle\XML\DomDocumentExtension as DomDocument;

/**
 * This class render an xml file
 * Using format RSS 2.0
 */
class RssRender implements RenderInterface {

    protected $xmlManager;
    protected $config;
    protected $items;
    protected $router;

    public function __construct() {
        
    }

    /**
     * @throw \InvalidArgumentException
     * @return void
     */
    public function save() {
        // First test if we need to upgrade or create the rss file
        if (isset($config['filename'])) {
            $filename = $config['filename'];
        } else {
            $e = explode('\\', $this->config['class']);
            $filename = $e[count($e) - 1];
        }

        $filename = __DIR__ . '/../Resources/public/rss/' . $filename . 'Rss.xml';
        $this->xmlManager = new XMLManager($filename);


        if (!file_exists($filename)) {
            $channel = $this->init();
        } else {
            $channel = $this->update();
        }

        $this->writeItems($channel);

        $this->xmlManager->save();
    }

    /**
     * Set the configuration
     * @param array $config 
     */
    public function setConfig(array $config) {
        $this->config = $config;
    }

    /**
     * Set the items who will be register
     * @param array $items 
     */
    public function setItems(array $items) {
        $this->items = $items;
    }

    /**
     * Set the router
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $r 
     */
    public function setRouter(\Symfony\Bundle\FrameworkBundle\Routing\Router $r) {
        $this->router = $r;
    }

    /**
     * Set the host who will be needed to finalize links
     * @param type $host 
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * Init the rss file with configuration
     * @return type 
     */
    private function init() {
        $root = $this->xmlManager->getXml()->createElement('rss');
        $root->setAttribute('version', '2.0');
        $root = $this->xmlManager->getXml()->appendChild($root);

        $channel = $this->xmlManager->getXml()->createElement('channel');
        $channel = $root->appendChild($channel);

        $this->xmlManager->addTextNode('description', $this->config['description'], $channel);
        $this->xmlManager->addTextNode('pubDate', date('D, j M Y H:i:s e'), $channel);
        $this->xmlManager->addTextNode('lastBuildDate', date('D, j M Y H:i:s e'), $channel);
        $this->xmlManager->addTextNode('link', 'http://' . $this->host . $this->router->generate($this->config['route']), $channel);
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

        return $channel;
    }

    /**
     * Update the feed with a (maybe) new configuration
     * @return \DOMNode channel 
     */
    private function update() {

        $channel = $this->xmlManager->getXml()->getElementsByTagName('channel')->item(0);
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
                $channel->removeChild($this->xmlManager->getXml()->getElementsByTagName('item')->item($l - 1));
            }
        }

        return $channel;
    }

    /**
     * Check if the item need to be written or created ans make one of this actions
     * @param \DOMNode $channel 
     */
    private function writeItems(\DOMNode $channel) {
        $ci = count($this->items);
        //$rc = new \ReflectionClass($this->config['class']);

        for ($i = 0; $i < $ci; $i++) {
            if (( $itemNode = $this->exists($this->items[$i])) === false) {
                $itemNode = $this->xmlManager->getXml()->createElement('item');
                $this->writeItem($itemNode, $this->items[$i], $channel);
            } else {

                $this->updateItem($itemNode, $this->items[$i], $channel);
            }
        }


        $itemNode = $channel->appendChild($itemNode);
    }

    /**
     * Transform an item in XML
     * @param \DOMNode $nodeItem
     * @param RssItemInterface $item
     * @param \DOMNode $parent 
     */
    private function writeItem(\DOMNode $nodeItem, RssItemInterface $item, \DOMNode $parent) {

        $this->xmlManager->addTextNode('title', $item->getRssTitle(), $nodeItem);
        $this->xmlManager->addTextNode('description', $item->getRssDescription(), $nodeItem);

        $rc = new \ReflectionClass($item);

        $route = $item->getRssRoute();
        if (is_array($route)) {

            $this->xmlManager->addTextNode('description', $this->router->generate($route[0], $route[1]), $nodeItem);
        } else {

            $this->xmlManager->addTextNode('description', $this->router->generate($route), $nodeItem);
        }
        $this->xmlManager->addTextNode('pubDate', date('D, j M Y H:i:s e'), $nodeItem);

        if ($rc->hasMethod('getRssAuthor')) {
            $this->xmlManager->addTextNode('author', $item->getRssAuthor(), $nodeItem);
        }

        if ($rc->hasMethod('getRssCategory')) {
            $this->xmlManager->addTextNode('category', $item->getRssCategory(), $item);
        }

        if ($rc->hasMethod('getRssCommentRoute')) {
            $commentRoute = $item->getRssCommentRoute();
            if (is_array($commentRoute)) {

                $this->xmlManager->addTextNode('comment', 'http://' . $this->host . $this->router->generate($commentRoute[0], $commentRoute[1]), $nodeItem);
            } else {

                $this->xmlManager->addTextNode('comment', 'http://' . $this->host . $this->router->generate($commentRoute), $nodeItem);
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

            $nodeItem->appendChild($enclosure);
        }

        $id = $item->getRssId();
        if (!empty($id)) {

            $this->xmlManager->addTextNode('guid', $item->getRssId(), $nodeItem);
        } else {
            throw new \InvalidArgumentException('The method « getRssId » on « ItemInterface » can\'t be empty. The problem may be that you register your RSS feed before you register your entity in your database.');
        }
    }

    /**
     * Remove the content of the item who need to be updated and write into him
     * @param \DOMNode $nodeItem
     * @param RssItemInterface $item
     * @param \DOMNode $channel 
     */
    private function updateItem(\DOMNode $nodeItem, RssItemInterface $item, \DOMNode $channel) {
        $l = $nodeItem->childNodes->length;
        for ($i = 0; $i < $l; $i++) {
            $nodeItem->removeChild($nodeItem->childNodes->item(0));
        }

        $this->writeItem($nodeItem, $item, $channel);
    }

    /**
     * Check if the item exists in the file
     */
    private function exists($item) {
        $l = $this->xmlManager->getXml()->getElementsByTagName('guid')->length;
        $itemNode = null;
        for ($i = 0; $i < $l || $itemNode !== null; $i++) {

            $guidNode = $this->xmlManager->getXml()->getElementsByTagName('guid')->item($i);


            if ($guidNode->childNodes->length != 0) {

                $id = $guidNode->childNodes->item(0)->wholeText;

                if ($id == $item->getId()) {

                    $itemNode = $guidNode->parentNode;
                }
            } else {

                throw new \InvalidArgumentException('Error in the XML file, guid (id) is required.');
            }
        }

        if ($itemNode === null) {
            return false;
        } else {
            return $itemNode;
        }
    }

}
