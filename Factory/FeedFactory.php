<?php

namespace Nekland\FeedBundle\Factory;


use Symfony\Component\DependencyInjection\ContainerAware;

use Nekland\FeedBundle\Feed;
use Nekland\FeedBundle\Renderer\RendererInterface;
use Nekland\FeedBundle\Loader\LoaderInterface;

/**
 * This class represent an agnostic feed, Atom, Rss, etc..
 *
 * @throws \InvalidArgumentException
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek-
 */
class FeedFactory extends ContainerAware
{
    /**
     * Will contains all feeds.
     * @var array|Feed
     */
    protected $feeds;

    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $feed
     * @return bool true if the feed exists
     */
    public function has($feed)
    {
        return isset($this->config['feeds'][$feed]);
    }

    /**
     * Renders a feed
     *
     * @param $feed
     * @param $renderer
     * @return
     */
    public function render($feed, $rendererName)
    {
        $renderer = $this->getRenderer($rendererName);

        return $renderer->render($this->get($feed));
    }

    /**
     * Loads a feed
     *
     * @param $feedName
     * @param $loader
     * @return Feed
     */
    public function load($feedName, $loaderName='rss_file')
    {
        $loader = $this->getLoader($loaderName);
        $feed = $this->get($feedName);
        $loadedFeed = $loader->load($feed->getFilename($loader->getFormat()));

        return $this->feeds[$feedName] = $feed->merge($loadedFeed);
    }

    /**
     * @throws \InvalidArgumentException
     * @param $feed
     * @return Feed
     */
    public function get($feed)
    {
        if(!$this->has($feed)) {
            throw new \InvalidArgumentException('The required feed is not defined. Check your configuration.');
        }
        
        if(!isset($this->feeds[$feed])) {
            $this->feeds[$feed] = new Feed($this->config['feeds'][$feed]);
        }
        
        return $this->feeds[$feed];
    }

    /**
     * @param $name
     * @return RendererInterface
     */
    protected function getRenderer($name)
    {
        if (isset($this->config['renderers'][$name])) {
            return $this->container->get($this->config['renderers'][$name]['id']);
        }

        throw new \InvalidArgumentException('Renderer '.$name.' doesn\'t exists');
    }

    /**
     * @param $name
     * @return LoaderInterface
     */
    protected function getLoader($name)
    {
        if (isset($this->config['loaders'][$name])) {
            return $this->container->get($this->config['loaders'][$name]['id']);
        }

        throw new \InvalidArgumentException('Loader '.$name.' doesn\'t exists');
    }

}
