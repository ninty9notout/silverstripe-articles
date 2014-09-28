<?php
class ArticleMonthArchive extends ArticleHolder {
	/**
	 * Enable or disable sitemap functionality (CMS only)
	 * @var bool
	 */
	static $enable_sitemap = false;

	/**
	 * By default {@link Page} cannot be root
	 * @var bool
	 */
	static $can_be_root = false;
	
	static $allowed_children = array('Article');

	static $default_child = 'NewsArticle';

	/**
	 * Fetch all articles that were created in the month this archive is for.
	 *
	 * @return DataList
	 */
	public function Articles() {
		return DataList::create('Article')
			->where(sprintf("ParentID = %d", $this->ID))
			->sort('Published DESC');
	}

	/**
	 * Count how many articles there are for the month this archive is for.
	 *
	 * @return int
	 */
	public function ArticleCount() {
		return $this->Articles()->count();
	}

	/**
	 * A simple test to check if an article from this archive is being displayed.
	 *
	 * @return boolean
	 */
	public function IsSection() {
		$url = preg_split('@/@', Controller::curr()->request->getVar('url'), null, PREG_SPLIT_NO_EMPTY);

		return isset($url[1]) && $url[1] == $this->Parent->URLSegment && isset($url[2]) && $url[2] == $this->URLSegment;
	}

	/**
	 * A simple test to check if this article archive is being displayed.
	 *
	 * @return boolean
	 */
	public function IsActive() {
		$url = preg_split('@/@', Controller::curr()->request->getVar('url'), null, PREG_SPLIT_NO_EMPTY);

		return isset($url[1]) && $url[1] == $this->Parent->URLSegment && isset($url[2]) && $url[2] == $this->URLSegment;
	}
}