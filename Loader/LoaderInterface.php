<?php

namespace Nekland\Bundle\FeedBundle\Loader;

use Nekland\Bundle\FeedBundle\Feed;

/**
 * Interface for all Feed loaders
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
interface LoaderInterface
{
    /**
     * @abstract
     * @param $feedContent
     * @return Feed
     */
    public function load($feedContent);

    /**
     * @abstract
     * @return string (ex: "rss")
     */
    public function getFormat();
}
