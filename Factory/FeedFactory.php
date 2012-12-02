<?php

namespace Nekland\Bundle\FeedBundle\Factory;


use Symfony\Component\DependencyInjection\ContainerAware;

use Nekland\Bundle\FeedBundle\Feed;
use Nekland\Bundle\FeedBundle\Renderer\RendererInterface;
use Nekland\Bundle\FeedBundle\Loader\LoaderInterface;

/**
 * This class represent an agnostic feed, Atom, Rss, etc..
 *
 * @throws \InvalidArgumentException
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev+github@gmail.com>
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

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $config
     */
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
     * @param $rendererName
     *
     * @return string
     */
    public function render($feed, $rendererName='rss')
    {
        $renderer = $this->getRenderer($rendererName);

        return $renderer->render($this->get($feed));
    }

    /**
     * Loads a feed
     *
     * @param $feedName
     * @param $loaderName
     * @return Feed
     */
    public function load($feedName, $loaderName = 'rss_file')
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
     *
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
