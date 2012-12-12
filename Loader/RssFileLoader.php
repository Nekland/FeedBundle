<?php

namespace Nekland\Bundle\FeedBundle\Loader;

/**
 * Loads an RSS file
 * @author <yohan@giarelli.org>
 * @author Nek' <nek.dev+github@gmail.com>
 */
class RssFileLoader extends RssLoader implements FileLoaderInterface
{
    protected $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function load($filename)
    {
        $filename = sprintf('%s/%s', $this->basePath, $filename);
        if(file_exists($filename)) {

            return parent::load($this->getContent($filename));
        } else {

            return new \Nekland\Bundle\FeedBundle\Feed(array('class' => 'Nekland\\Bundle\\FeedBundle\\Item\\GenericItem'));
        }
    }

    public function getContent($filename)
    {
        return file_get_contents($filename);
    }
}
