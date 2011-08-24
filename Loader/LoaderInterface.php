<?php

namespace Nekland\FeedBundle\Loader;

use Nekland\FeedBundle\Feed;

/**
 * Interface for all Feed loaders
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
interface LoaderInterface
{
    /**
     * @abstract
     * @param $filename
     * @return Feed
     */
    public function load($feedContent);

    /**
     * @abstract
     * @return string (ex: "rss")
     */
    public function getFormat();
}