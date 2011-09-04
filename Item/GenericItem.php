<?php

namespace Nekland\FeedBundle\Item;

use Nekland\FeedBundle\Item\ExtendedItemInterface;

/**
 * This class represent a generic Feed item (used for feed loading)
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev@gmail.com>
 */
class GenericItem implements ExtendedItemInterface, AtomItemInterface
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
        $routes,
        $summary,
        $titleType,
        $contentType,
        $summaryType,
        $contributors,
        $contentLanguage
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

    public function setFeedDate(\DateTime $date)
    {
        $this->date = $date;
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

    public function setFeedRoutes(array $route){
        $this->routes = $route;
    }

    public function getFeedRoutes()
    {
        return $this->routes;
    }

    public function getFeedSummary() {
        return $this->summary;
    }

    public function setFeedSummary($summary) {
        $this->summary = $summary;
    }
    
    public function getAtomTitleType() {
    	return $this->titleType;
    }
    
    public function setAtomTitleType($type) {
    	$this->titleType = $type;
    }

    public function setAtomContentType($type) {
    	$this->contentType =$type;
    }
    
    public function getAtomContentType() {
    	return $this->contentType;
    }
    
    public function setAtomContributors(array $contributors) {
    	$this->contributors = $contributors;
    }
    
    public function getAtomContributors() {
    	return $this->contributors;
    }
    
    public function setAtomSummaryType($summary) {
    	$this->summaryType = $summary;
    }
    
    public function getAtomSummaryType() {
    	return $this->summaryType;
    }

    public function getAtomContentLanguage() {
    	return $this->contentLanguage;
    }
    
    public function setAtomContentLanguage($lang) {
    	$this->contentLanguage = $lang;
    }
}