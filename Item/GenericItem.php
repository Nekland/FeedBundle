<?php

namespace Nekland\FeedBundle\Item;

use Nekland\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class represent a generic Feed item (used for feed loading)
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev@gmail.com>
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
        $enclosure,
        $route
    ;

    public function setFeedAuthor($author)
    {
        $this->author = $author;
    }

    public function getFeedAuthor()
    {
        return $this->author;
    }

    public function setFeedCommentRoute($commentRoute)
    {
        $this->commentRoute = $commentRoute;
    }

    public function getFeedCommentRoute()
    {
        return $this->commentRoute;
    }

    public function setFeedDescription($description)
    {
        $this->description = $description;
    }

    public function getFeedDescription()
    {
        return $this->description;
    }

    public function setFeedEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    public function getFeedEnclosure()
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

    public function setFeedTitle($title)
    {
        $this->title = $title;
    }

    public function getFeedTitle()
    {
        return $this->title;
    }

    public function setFeedCategory($category)
    {
        $this->category = $category;
    }

    public function getFeedCategory()
    {
        return $this->category;
    }

    public function setFeedDate($date)
    {
        $this->date = new \DateTime($date);
    }

    public function getFeedDate()
    {
        return $this->date;
    }

    public function setFeedLink($link)
    {
        $this->link = $link;
    }

    public function getFeedLink()
    {
        return $this->link;
    }

    public function setFeedRoute($route){
        $this->route = $route;
    }

    public function getFeedRoute()
    {
        return '';
    }


}
