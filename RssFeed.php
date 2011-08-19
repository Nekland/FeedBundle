<?php

namespace Nekland\FeedBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class RssFeed {
    protected $router;
    
    /**
     * Config example:
     * 'class' => 'Nekland\BlogBundle\Entity\Article' (who must implement FeedBundle\Item\RssItem)
     * 'title' => 'My Rss title'
     * 'description' => 'My Rss description'
     * 'route' => 'My Rss site route' (home if not defined)
     */
    protected $config;
    
    /**
     * @var string $filename
     */
    protected $filename;

    public function __construct(Router $router, array $config) {
        $this->router = $router;
        $this->config = $config;
        
        
    }
    
    public function add($item){
        $rc = new \ReflectionClass($this->config['class']);
        if(!$rc->isInstance($item))
            throw new \InvalidArgumentException();
        
        $rc = new \ReflectionClass($item);
        if ($this->rc->hasMethod('getFilename')) {
            $name = $item->getFilename();
        } else {
            $e = explode('\\', get_class($item));
            $name = $e[count($e) - 1];
        }
        $this->new = file_exists($this->filename = __DIR__ . '/../Resources/public/rss/' . $name . 'Rss.xml') ? false : true;
    }
}
