<?php

namespace Nekland\FeedBundle\Factory;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nekland\FeedBundle\Feed;

class FeedFactory
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
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;
    
    /**
     * Host of the site, needed for writing routes.
     * @var string 
     */
    protected $host;
    
    public function __construct(ContainerInterface $container, Router $router, array $config)
    {
        $this->router = $router;
        $this->config = $config['feeds'];
        $this->host = $container->get('request')->getHost();
    }

    public function setConfig(array $config)
    {
        $this->config = $config['feeds'];
    }

    /**
     * @param $feed
     * @return bool true if the feed exists
     */
    public function has($feed)
    {
        return isset($this->config[$feed]);
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
            $this->feeds[$feed] = new Feed($this->router, $this->config[$feed], $this->host);
        }
        
        return $this->feeds[$feed];
    }
}
