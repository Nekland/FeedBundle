<?php

namespace Nekland\FeedBundle\Loader;

class RssFileLoader extends RssLoader implements FileLoaderInterface
{
    protected $basePath;
    
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    protected function getContent($filename)
    {
        return file_get_contents(sprintf('%s/%s', $this->basePath, $filename));
    }
}
