<?php

namespace Nekland\RssBundle\Item;

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
     * Return
     *
     */
    public function getRssLink();
    
    /*
     * What else you can implement ?
     */
}
