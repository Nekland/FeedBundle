<?php

namespace Nekland\FeedBundle\Render;

use Nekland\FeedBundle\XML\XMLManager;
//use Nekland\FeedBundle\XML\DomDocumentExtension as DomDocument;

/**
 * This class render an xml file
 * Using format RSS 2.0
 */

class RssRender implements RenderInterface {
    protected $xmlManager;
    protected $rss;
    protected $config;
    protected $items;
    protected $router;
    
    public function __construct() {
        
    }
    
    public function save() {
        // First test if we need to upgrade or create the rss file
        if(isset($config['filename'])) {
            $filename = $config['filename'];
        } else {
            $e = explode('\\', get_class($item));
            $filename = $e[count($e) - 1];
        }
        
        $filename = __DIR__ . '/../Resources/public/rss/' . $filename . 'Rss.xml';
        $this->xmlManager = new XMLManager($filename);
        
        /*
        if($this->new = !file_exists($this->filename)) {
        
            $this->rss = new DomDocument('1.0', 'utf-8');
        } else {
        
            $this->rss = new DomDocument();
            $this->rss->load($this->filename);
        }
        //*/
        if($isNew) {
            $this->init();
        } else {
            $this->update();
        }
        
        $this->upgradeFeeds();
    }
    
    public function setConfig(array $config) {
        $this->config = $config;
    }
    
    public function setItems(array $items) {
        $this->items = $items;
    }
    
    public function setRouter(Symfony\Bundle\FrameworkBundle\Routing\Router $r) {
        $this->router = $r;
    }
    
    private function init() {
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
        
        if(isset($this->config['copyright'])) {
            $this->xmlManager->addTextNode('copyright', $this->config['copyright'], $channel);
        }
        
        if(isset($this->config['managingEditor'])) {
            $this->xmlManager->addTextNode('managingEditor', $this->config['managingEditor'], $channel);
        }
        
        if(isset($this->config['generator'])) {
            $this->xmlManager->addTextNode('generator', $this->config['generator'], $channel);
        }
        
        if(isset($this->config['webMaster'])) {
            $this->xmlManager->addTextNode('webMaster', $this->config['webMaster'], $channel);
        }
        
        
        if(isset($this->config['image']) && isset($this->config['image']['url']) && isset($this->config['image']['title']) && isset($this->config['image']['link'])) {
            
            $image = $this->xmlManager->getXml()->createElement('image');
            $image = $channel->appendChild($image);
            $this->xmlManager->addTextNode('url', $this->config['image']['url'], $image);
            $this->xmlManager->addTextNode('title', $this->config['image']['title'], $image);
            $this->xmlManager->addTextNode('link', $this->config['image']['link'], $image);
            
            
            if(isset($this->config['image']['height'])) {
            
                $this->xmlManager->addTextNode('height', $this->config['image']['height'], $image);
            }
            if(isset($this->config['image']['width'])) {
                
                $this->xmlManager->addTextNode('width', $this->config['image']['width'], $image);
            }
        }
        
        if(isset($this->config['ttl'])) {
            $this->xmlManager->addTextNode('ttl', $this->config['ttl'], $channel);
        }
        
    }
    
    private function update() {
    
    // Setting the last publication (lastBuildDate)
        $lastBuild = $this->xmlManager->getXml()->getElementsByTagName('lastBuildDate')->item(0);
        $lastBuild->removeChild($lastBuild->firstChild);
        $text = $this->xmlManager->getXml()->createTextNode(date('D, j M Y H:i:s e'));
        $lastBuild->appendChild($text);
        
    // Deleting items if we have too much items
        $nbDel = count($this->items) - $this->config[''];
        
        for($i = 0; $i < $nbDel; $i++) {
            
        }
    }
}
