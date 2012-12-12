<?php

namespace Nekland\Bundle\FeedBundle\Item;

use Nekland\Bundle\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class represent a generic Feed item (used for feed loading)
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev@gmail.com>
 */
class GenericItem implements ExtendedItemInterface, AtomItemInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $feedId;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $commentRoute;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * @var array|string
     */
    protected $routes;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var
     */
    protected $titleType;

    /**
     * @var
     */
    protected $contentType;

    /**
     * @var
     */
    protected $summaryType;

    /**
     * @var array
     */
    protected $contributors;

    /**
     * @var string
     */
    protected $contentLanguage;

    /**
     * @param $author
     */
    public function setFeedAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getFeedAuthor()
    {
        return $this->author;
    }

    /**
     * @param $commentRoute
     */
    public function setFeedCommentRoute($commentRoute)
    {
        $this->commentRoute = $commentRoute;
    }

    /**
     * @return string
     */
    public function getFeedCommentRoute()
    {
        return $this->commentRoute;
    }

    /**
     * @param $description
     */
    public function setFeedDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFeedDescription()
    {
        return $this->description;
    }

    /**
     * @param $enclosure
     */
    public function setFeedEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return string
     */
    public function getFeedEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param $feedId
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
    }

    /**
     * @return string
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * @param $title
     */
    public function setFeedTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getFeedTitle()
    {
        return $this->title;
    }

    /**
     * @param $category
     */
    public function setFeedCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getFeedCategory()
    {
        return $this->category;
    }

    /**
     * @param \DateTime $date
     */
    public function setFeedDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getFeedDate()
    {
        return $this->date;
    }

    /**
     * @param $link
     */
    public function setFeedLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getFeedLink()
    {
        return $this->link;
    }

    /**
     * @param array $route
     */
    public function setFeedRoutes(array $route)
    {
        $this->routes = $route;
    }

    /**
     * @return array|string
     */
    public function getFeedRoutes()
    {
        return $this->routes;
    }

    /**
     * @return string
     */
    public function getFeedSummary()
    {
        return $this->summary;
    }

    /**
     * @param $summary
     */
    public function setFeedSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getAtomTitleType()
    {
        return $this->titleType;
    }

    /**
     * @param $type
     */
    public function setAtomTitleType($type)
    {
        $this->titleType = $type;
    }

    /**
     * @param $type
     */
    public function setAtomContentType($type)
    {
        $this->contentType = $type;
    }

    /**
     * @return mixed
     */
    public function getAtomContentType()
    {
        return $this->contentType;
    }

    /**
     * @param array $contributors
     */
    public function setAtomContributors(array $contributors)
    {
        $this->contributors = $contributors;
    }

    /**
     * @return array
     */
    public function getAtomContributors()
    {
        return $this->contributors;
    }

    /**
     * @param $summary
     */
    public function setAtomSummaryType($summary)
    {
        $this->summaryType = $summary;
    }

    /**
     * @return mixed
     */
    public function getAtomSummaryType()
    {
        return $this->summaryType;
    }

    /**
     * @return mixed
     */
    public function getAtomContentLanguage()
    {
        return $this->contentLanguage;
    }

    /**
     * @param $lang
     */
    public function setAtomContentLanguage($lang)
    {
        $this->contentLanguage = $lang;
    }
}
