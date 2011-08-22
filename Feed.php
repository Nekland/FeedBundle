<?php

namespace Nekland\FeedBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Nekland\FeedBundle\Render\RenderInterface;
use Nekland\FeedBundle\Item\ItemInterface;

class Feed {
    protected $router;
    protected $host;
    
    /**
     * Config example:
     * 'class' => 'Nekland\BlogBundle\Entity\Article' (who must implement FeedBundle\Item\RssItem)
     * 'title' => 'My Rss title'
     * 'description' => 'My Rss description'
     * 'route' => 'My Rss site route' (home if not defined)
     * @var array $config
     */
    protected $config;
    
    /**
     *
     * @var ItemInterface $items
     */
    protected $items;
    

    public function __construct(Router $router, array $config, $host) {
        $this->router = $router;
        $this->config = $config;
        
        $this->items = array();
        $this->host = $host;
    }
    
    /**
     *
     * @param type $item
     * @return Feed 
     */
    public function add(ItemInterface $item){
        $rc = new \ReflectionClass($this->config['class']);
        if(!$rc->isInstance($item)) {
            throw new \InvalidArgumentException('The class given MUST be an instance of "'.$this->config['class'].'".');
        }
        
        $this->items[] = $item;
        
        return $this;
    }
    
    /**
     * Render the feed with using a RenderInterface class
     * @param RenderInterface $render
     * @return Feed 
     */
    public function render(RenderInterface $render) {
        if(count($this->items) < 1){
            throw new \Exception('There are no items registered.');
        }
        $render->setConfig($this->config);
        $render->setItems($this->items);
        $render->setRouter($this->router);
        $render->setHost($this->host);
        
        $render->save();
        
        return $this;
    }
}
