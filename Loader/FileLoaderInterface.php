<?php

namespace Nekland\FeedBundle\Loader;

interface FileLoaderInterface
{
    function getContent($filename);
}