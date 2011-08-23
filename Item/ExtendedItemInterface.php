<?php

namespace Nekland\FeedBundle\Item;

interface ExtendedItemInterface extends ItemInterface
{
    /**
     * Somethink like array('nickname' => 'foo', 'email' => 'foo@bar.com', 'website' => 'http://foo.bar.com')
     * 
     * @return array
     */
    public function getAuthor();
    
    /**
     * @return string
     */
    public function getCategory();
    
    /**
     * @return string|array with [0] => 'route_name', [1] => params
     */
    public function getCommentRoute();
    
    /**
     * 
     * @return array with param1 => "value1", param3 => "value 2", ...
     */
    public function getEnclosure();
}
