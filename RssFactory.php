<?php

namespace Nekland\FeedBundle;

use RssFeed;
use AtomFeed;

class RssFactory {

    /**
     * The bundle configuration contains all the feeds configuration
     *
     * @var array $config
     */
    protected $config;
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    public function get($feed){
        $feed_configuration = $config[$feed];
        
    }
}
