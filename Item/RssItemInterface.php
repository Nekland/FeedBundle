<?php

namespace Nekland\FeedBundle\Item;

interface RssItem {
    /**
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getRssTitle();
    
    /**
     * Return the description of your rss, someting like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getRssDescription();
    
    /**
     * Return the route of your item
     * @return string|array with [0] => 'route_name', [1] => params
     */
    public function getRssRoute();
    
    /**
     * @return unique identifiant (for editing)
     */
    public function getRssId();
    
    /*
     * What else you can implement ?
     * Check the FullRssItemInterface.
     */
}
