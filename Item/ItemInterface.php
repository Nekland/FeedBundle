<?php

namespace Nekland\Bundle\FeedBundle\Item;

interface ItemInterface
{
    /*
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getFeedTitle();

    /*
     * Return the description of your rss, something like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getFeedDescription();

    /*
     * Return the route of your item
     * @return array with
     * [0]
     *      =>
     *      	['route']
     *      			=>
     *          			[0] =>  'route_name'
     *          			[1] =>  array of params of the route
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     * [1]
     *     	=>
     *     		['url'] => 'http://mywebsite.com'
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     */

    public function getFeedRoutes();

    /**
     * @return unique identifier (for editing)
     */
    public function getFeedId();

    /**
     * @abstract
     * @return \DateTime
     */
    public function getFeedDate();
}
