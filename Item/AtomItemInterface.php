<?php

namespace Nekland\FeedBundle\Item;


interface AtomItemInterface extends ItemInterface {
	/**
	 * @return string text | html | xhtml
	 */
	public function getAtomTitleType();
	public function getAtomContentType();
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