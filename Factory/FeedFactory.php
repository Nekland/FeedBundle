<?php

namespace Nekland\FeedBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerAware;

use Nekland\FeedBundle\Feed;
use Nekland\FeedBundle\Renderer\RendererInterface;

/**
 * This class represent an agnostic feed, Atom, Rss, etc..
 *
 * @throws \InvalidArgumentException
 * @author Yohan Giarelli <yohan@giarelli.org>
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

    public function render($feed, $renderer)
    {
        $renderer = $this->getRenderer($renderer);

        return $renderer->render($this->get($feed));
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


}
