<?php

namespace Nekland\FeedBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Nekland\FeedBundle\Render\RenderInterface;

class Feed {
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
    
    protected $type;
    
    protected $items;
    
    protected $new;

    public function __construct(Router $router, array $config, $type='rss') {
        $this->router = $router;
        $this->config = $config;
        if(!in_array(($this->type = $type), array('rss', 'atom'))) {
            throw new \InvalidArgumentException('The type of the feed must be rss or atom');
        }
        
//        $rc = new \ReflectionClass($this->config['class']);
//        if ($rc->hasMethod('getFilename')) {
//        
//            $name = $item->getFilename();
//        } else {
//        
//            $e = explode('\\', get_class($item));
//            $name = $e[count($e) - 1];
//        }
//        $this->new = file_exists($this->filename = __DIR__ . '/../Resources/public/rss/' . $name . 'Rss.xml') ? false : true;
        $this->items = array();
    }
    
    public function add($item){
        $rc = new \ReflectionClass($this->config['class']);
        if(!$rc->isInstance($item)) {
            throw new \InvalidArgumentException('The class given MUST be an instance of "'.$this->config['class'].'".');
        }
        
        $this->items[] = $item;
        
        return $this;
    }
    
    public function render(RenderInterface $render) {
        if(count($this->items) < 1){
            throw new \Exception('There are no items registered.');
        }
        $render->setConfig($this->config);
        $render->setItems($this->items);
        $render->setRouter($this->router);
        
        $render->save();
        
        return $this;
    }
}
