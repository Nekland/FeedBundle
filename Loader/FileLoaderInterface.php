<?php

namespace Nekland\Bundle\FeedBundle\Loader;

/**
 * This interface represent a From-file loader
 */
interface FileLoaderInterface
{
    function getContent($filename);
}
