<?php

namespace Nekland\FeedBundle\Loader;

use Nekland\FeedBundle\Feed;

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