<?php

namespace Nekland\FeedBundle\Item;

interface MoreParamsItemInterface {

    /**
     * @return string text|html
     */
    public function getFeedTitleType();

    /**
     * @return array of link
     * [0]
     *      =>  [0] => 'route name'
     *          [1] => array of parameters
     * [1]
     *      =>  'route name (if no paramaters)'
     *
     * ...
     *
     */
    public function getMoreFeedRoutes();
}