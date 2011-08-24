<?php

namespace Nekland\FeedBundle\Item;

use Nekland\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class represent a generic Feed item (used for feed loading)
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
class GenericItem implements ExtendedItemInterface
{
    protected
        $title,
        $date,
        $category,
        $description,
        $link,
        $feedId,
        $author,
        $commentRoute,
        $enclosure
    ;

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setCommentRoute($commentRoute)
    {
        $this->commentRoute = $commentRoute;
    }

    public function getCommentRoute()
    {
        return $this->commentRoute;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    public function getEnclosure()
    {
        return $this->enclosure;
    }

    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
    }

    public function getFeedId()
    {
        return $this->feedId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setDate($date)
    {
        $this->date = new \DateTime($date);
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getRoute()
    {
        return '';
    }


}
