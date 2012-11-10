<?php

namespace Nekland\Bundle\FeedBundle\Item;

/**
 * Interface for Item that manage Atom-specific attributes
 *
 * @author Nek' <nek.dev+github@gmail.com>
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
interface AtomItemInterface extends ItemInterface
{
    /**
     * @return string text
     */
    public function getAtomTitleType();

    /**
     * @abstract
     * @return mixed
     */
    public function getAtomContentType();

    /**
     * @abstract
     * @return mixed
     */
    public function getAtomSummaryType();

    /**
     * @return array of authors
     */
    public function getAtomContributors();

    /**
     * @return string like "en"
     */
    public function getAtomContentLanguage();
}
