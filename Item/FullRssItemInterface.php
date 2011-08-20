<?php

namespace Nekland\FeedBundle\Item;

interface FullRssItemInterface extends RssItemInterface {
    /**
     * Somethink like "NickName (Real Name)"
     * @return string
     */
    public function getRssAuthor();
    
    /**
     * @return string
     */
    public function getRssCategory();
    
    /**
     * @return string|array with [0] => 'route_name', [1] => params
     */
    public function getRssCommentRoute();
    
    /**
     * 
     * @return array with param1 => "value1", param3 => "value 2", ...
     */
    public function getRssEnclosure();
    
    /**
     * Unique identifiant
     * @return string
     */
    public function getRssGuid();
}
