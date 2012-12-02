<?php

/**
 * This class render an xml file
 * Using format Atom 1
 *
 * @author Nek' <nek.dev+github@gmail.com>
 */

namespace Nekland\Bundle\FeedBundle\Renderer;

use Symfony\Component\Routing\Router;
use Nekland\Bundle\FeedBundle\Feed;
use Nekland\Bundle\FeedBundle\XML\XMLManager;
use Nekland\Bundle\FeedBundle\Item\ItemInterface;


class AtomRenderer implements RendererInterface {

    /**
     * @var Symfony\Component\Routing\Router
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
    public function render(Feed $feed) {
        $filename = sprintf('%s/%s', $this->basePath, $feed->getFilename('atom'));
        if (is_file($filename)) {
            unlink($filename);
        }

        $xml = new XMLManager($filename);
        $this->init($xml, $feed);
        $this->writeItems($xml, $feed);
        $xml->save();
    }

    private function init(XMLManager $xml, Feed $feed) {
        $root = $xml->getXml()->createElement('feed');
        $root->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $root = $xml->getXml()->appendChild($root);

        $xml->addTextNode('title', $feed->get('title'), $root);

        if($subtitle = $feed->get('subtitle'))
        $xml->addTextNode('subtitle', $subtitle, $root);

        $date = new \DateTime();
        $xml->addTextNode('updated', $date->format(\DateTime::ATOM), $root);

        if($id = $feed->get('id'))
        $xml->addTextNode('id', $id, $root);

        if(null !== $feed->get('icon')) {
            $xml->addTextNode('icon', $feed->get('icon'), $root);
        }

        if(null !== $feed->get('image')) {
            $image = $feed->get('image');
            $xml->addTextNode('logo', $image['url'], $root);
        }

        if(null !== $feed->get('rights')) {
            $xml->addTextNode('rights', $feed->get('rights'), $root);
        }

        if(null !== $feed->get('url')) {
            $link = $xml->getXml()->createElement('link');
            $link->setAttribute('href', $feed->get('url'));
            $link = $root->appendChild($link);
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

        $type = $this->itemHas($item, 'getAtomContentType') ? $item->getAtomContentType() : null;
        $content = $xml->addTextNode('content', $type === 'xhtml' ? $item->getFeedDescription() : htmlentities($item->getFeedDescription(), ENT_COMPAT, 'UTF-8'), $nodeItem);
        if(!empty($type)){
            $content->setAttribute('type', $type);
        }
        if($this->itemHas($item, 'getAtomContentLanguage')){
            $content->setAttribute('xml:lang', $item->getAtomContentLanguage());
        }


        $id=$item->getFeedId();
        if(empty($id))
        throw new \InvalidArgumentException('The method « getFeedId » CAN\'T return an empty value.');
        $xml->addTextNode('id', $id, $nodeItem);


        $xml->addTextNode('published', $item->getFeedDate()->format(\DateTime::ATOM), $nodeItem);

        $date = new \DateTime();
        $xml->addTextNode('updated', $item->getFeedDate()->format(\DateTime::ATOM), $nodeItem);

        if ($this->itemHas($item, 'getFeedAuthor')) {
            if ($author = $item->getFeedAuthor()) {
                $authorNode = $this->createAuthor($xml, $author);
                $nodeItem->appendChild($authorNode);
            }
        }
        if ($this->itemHas($item, 'getSummary')) {
            $type = $this->itemHas($item, 'getAtomSummaryType') ? $item->getAtomSummaryType() : null;
            $summary = $xml->addTextNode('category', $type === 'xhtml' ? $item->getFeedDescription() : htmlentities($item->getFeedSummary(), ENT_COMPAT, 'UTF-8'), $nodeItem);
            if(!empty($type)){
                $summary->setAttribute('type', $type);
            }
        }
        if ($this->itemHas($item, 'getAtomContributors')) {
            if ($contributors = $item->getAtomContributors($item)) {
                foreach($contributors as $contributor) {
                    $contribNode = $this->createContributor($xml, $contributor);
                    $nodeItem->appendChild($contribNode);
                }
            }
        }

        $routes = $item->getFeedRoutes();
        foreach($routes as $route) {
            $linkNode = $this->createLink($xml, $route);
            $nodeItem->appendChild($linkNode);
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
        $itemNode = $xml->getXml()->createElement('entry');
        $channelNode = $xml->getXml()->getElementsByTagName('feed')->item(0);
        $channelNode->appendChild($itemNode);

        return $itemNode;
    }

    /**
     *
     * @param XMLManager $xml
     * @param array      $author
     *
     * @return \DOMNode
     */
    private function createAuthor(XMLManager $xml, array $author) {
        $authorNode = $xml->getXml()->createElement('author');

        if(isset($author['name'])) {
            $xml->addTextNode('name', $author['name'], $authorNode);
        }

        if(isset($author['website'])) {
            $xml->addTextNode('uri', $author['website'], $authorNode);
        }

        if(isset($author['email'])) {
            $xml->addTextNode('email', $author['email'], $authorNode);
        }

        return $authorNode;
    }

    /**
     *
     * @param XMLManager $xml
     * @param array      $contributor
     *
     * @return \DOMNode
     */
    private function createContributor(XMLManager $xml, array $contributor) {
        $contributorNode = $xml->getXml()->createElement('contributor');

        if(isset($author['name'])) {
            $xml->addTextNode('name', $contributor['name'], $contributorNode);
        }

        if(isset($author['website'])) {
            $xml->addTextNode('uri', $contributor['website'], $contributorNode);
        }

        if(isset($author['email'])) {
            $xml->addTextNode('email', $contributor['email'], $contributorNode);
        }

        return $contributorNode;
    }

    /**
     *
     * @param XMLManager $xml
     * @param array      $route
     * @throws \InvalidArgumentException
     *
     * @return \DOMNode
     */
    private function createLink(XMLManager $xml, array $route) {
        $routeNode = $xml->getXml()->createElement('link');
        $url = '';
        if(!empty($route['route']) && is_array($route['route'])) {

            $url = $this->router->generate($route['route'][0], $route['route'][1]);

        } else if(!empty($route['url'])) {

            $url = $route['url'];

        } else {
            throw new \InvalidArgumentException('The method getFeedRoutes must be an array of links. Check the ItemInterface.php for more details.');
        }

        $routeNode->setAttribute('href', $url);

        foreach($route as $attribute => $value) {
            if($attribute != 'route' && $attribute != 'url') {
                $routeNode->setAttribute($attribute, $value);
            }
        }

        return $routeNode;
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
