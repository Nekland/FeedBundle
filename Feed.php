<?php

namespace Nekland\FeedBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Nekland\FeedBundle\Item\ItemInterface;
use Nekland\FeedBundle\Item\GenericItem;

class Feed implements \ArrayAccess, \Countable, \IteratorAggregate
{

    /**
     * Config example:
     * 'class' => 'Nekland\BlogBundle\Entity\Article' (who must implement FeedBundle\Item\RssItem)
     * 'title' => 'My Rss title'
     * 'description' => 'My Rss description'
     * 'route' => 'My Rss site route' (home if not defined)
     * @var array $config
     */
    protected $config;

    /**
     * @var array|ItemInterface
     */
    protected $items;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->items = array();
    }

    /**
     * @throws \InvalidArgumentException
     * @param ItemInterface $item
     * @return Feed
     */
    public function add(ItemInterface $item)
    {
        $rc = new \ReflectionClass($this->config['class']);
        if (!($rc->isInstance($item) || $item instanceof GenericItem)) {
            throw new \InvalidArgumentException('The class given MUST be an instance of "' . $this->config['class'] . '".');
        }

        $this->items[] = $item;

        $this->autoSlice();

        return $this;
    }


    /**
     * Removes an item by Id
     *
     * @throws \InvalidArgumentException
     * @param $id
     * @return Feed
     */
    public function remove($id)
    {
        $success = false;
        foreach ($this->items as $i => $item) {
            if ($item->getId() === $id) {
                unset($this->items[$i]);
                $success = true;
                break;
            }
        }

        if (false === $success) {
            throw new \InvalidArgumentException('Unknown item');
        }

        return $this;
    }

    /**
     * Replace an Item by an other
     * 
     * @throws \InvalidArgumentException
     * @param $id
     * @param ItemInterface $newItem
     * @return Feed
     */
    public function replace($id, ItemInterface $newItem)
    {
        $success = false;
        foreach ($this->items as $i => $item) {
            if ($item->getFeedId() == $id) {
                $this->items[$i] = $newItem;
                $success = true;
                break;
            }
        }

        if (false === $success) {
            throw new \InvalidArgumentException('Unknown item');
        }

        return $this;
    }

    public function getFilename($format)
    {
        return strtr($this->config['filename'], array('%format%' => $format));
    }

    /**
     * Return a configuration param
     *
     * @param $param
     * @return mixed
     */
    public function get($param, $default = null)
    {
        return isset($this->config[$param]) ? $this->config[$param] : $default;
    }

    public function set($param, $value)
    {
        $this->config[$param] = $value;
    }

    public function merge(Feed $feed)
    {
        $this->items = array();

        foreach ($feed as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * Retrieve an external iterator
     * @return Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @param $offset
     * @return bool if $offset exists
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param $offset
     * @return ItemInterface
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param $offset
     * @param ItemInterface
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof ItemInterface) {
            throw new \InvalidArgumentException('Feed items must implement ItemInterface');
        }
        $this->items[$offset] = $value;
    }


    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }


    /**
     * @param $config
     * @return Feed
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Auto slice the items to keep the good number of items
     * @return Feed
     */
    protected function autoSlice()
    {
        if (count($this) > $this->config['max_items']) {
            $this->items = array_slice(
                $this->items,
                count($this) - $this->config['max_items'],
                $this->config['max_items']
            );
        }

        return $this;
    }
}
