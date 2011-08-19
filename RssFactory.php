<?php

namespace Nekland\FeedBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;


class RssFactory {

    /**
     * The bundle configuration contains all the feeds configuration
     *
     * @var array $config
     */
    protected $config;
    
    /**
     * Will contains all feeds.
     *
     */
    protected $feeds;
    
    protected $router;
    
    public function __construct(Router $router, array $config) {
        $this->config = $config;
        $this->router = $router;
    }
    
    public function get($feed){
        if(!isset($config[$feed])) {
            throw new \InvalidArgumentException('The required feed is not defined. Check your configuration.');
        }
        
        if(!isset($this->feeds[$feed])) {
            $feed_configuration = $config[$feed];
            
            $this->feeds[$feed] = new Feed($this->router, $feed_configuration, $feed_configuration['type']);
        }
        
        return $this->feeds[$feed];
    }
}
