<?php

namespace Nekland\FeedBundle\Loader;

class RssFileLoader extends RssLoader implements FileLoaderInterface
{
    protected $basePath;
    
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function load($filename)
    {
        return parent::load($this->getContent($filename));
    }

    public function getContent($filename)
    {
        return file_get_contents(sprintf('%s/%s', $this->basePath, $filename));
    }
}
