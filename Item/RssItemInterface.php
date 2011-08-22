<?php

namespace Nekland\FeedBundle\Item;

interface RssItemInterface extends ItemInterface {
    /*
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getRssTitle();
    
    /*
     * Return the description of your rss, someting like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getRssDescription();
    
    /*
     * Return the route of your item
     * @return string|array with [0] => 'route_name', [1] => params
     */
    public function getRssRoute();
    

    
    /*
     * What else you can implement ?
     * Check the FullRssItemInterface.
     */
}
