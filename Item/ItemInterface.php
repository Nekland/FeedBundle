<?php

namespace Nekland\FeedBundle\Item;

interface ItemInterface
{
    /*
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getFeedTitle();

    /*
     * Return the description of your rss, someting like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getFeedDescription();

    /*
     * Return the route of your item
     * @return string|array with [0] => 'route_name', [1] => params
     */

    public function getFeedRoute();

    /**
     * @return unique identifiant (for editing)
     */
    public function getFeedId();

    /**
     * @abstract
     * @return \DateTime
     */
    public function getFeedDate();
}
