<?php

namespace Nekland\Bundle\FeedBundle\Item;

interface ExtendedItemInterface extends ItemInterface
{
    /**
     * Somethink like array('name' => 'foo', 'email' => 'foo@bar.com', 'website' => 'http://foo.bar.com')
     *
     * @return array
     */
    public function getFeedAuthor();

    /**
     * @return string
     */
    public function getFeedCategory();

    /**
     * @return string|array with [0] => 'route_name', [1] => params
     */
    public function getFeedCommentRoute();

    /**
     *
     * @return array with param1 => "value1", param3 => "value 2", ...
     */
    public function getFeedEnclosure();

    /**
     * @return string
     */
    public function getFeedSummary();
}
