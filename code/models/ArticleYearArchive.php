<?php
class ArticleYearArchive extends ArticleHolder {
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

	static $allowed_children = array(
		'ArticleMonthArchive',
		'Article'
	);

	static $default_child = 'ArticleMonthArchive';

	/**
	 * Fetch all articles that were created in the year this archive is for.
	 *
	 * @return DataList
	 */
	public function Articles() {
		return DataList::create('Article')
			->where(sprintf("Published LIKE '%s-%%'", $this->URLSegment))
			->sort('Published DESC');
	}

	/**
	 * Count how many articles there are for the year this archive is for.
	 *
	 * @return int
	 */
	public function ArticleCount() {
		return $this->Articles()->count();
	}

	/**
	 * Return all month archives for this year.
	 *
	 * @return DataList
	 */
	public function ArticleMonthArchive() {
		return DataList::create('ArticleMonthArchive')
			->where(sprintf("ParentID = %d", $this->ID))
			->sort('URLSegment DESC');
	}

	/**
	 * Check if an archive page for the specific month exists otherwise create it.
	 *
	 * @param int $month The month archive to look for
	 * @return ArticleMonthArchive The existing or newly created month archive
	 */
	public function FindOrCreateMonthArchive($month) {
		// First, try to find the month archive
		$monthArchive = DataObject::get_one(
			'ArticleMonthArchive',
			sprintf("ParentID = %d AND URLSegment = '%s'", $this->ID, Convert::raw2sql($month))
		);

		// Create a new archive if there isn't an existing one
		if(!$monthArchive) {
			$monthArchive = new ArticleMonthArchive();
			$monthArchive->Title = date('F', mktime(0, 0, 0, $month, 1));
			$monthArchive->ParentID = $this->ID;
			$monthArchive->URLSegment = $month;
			$monthArchive->SortOrder = 12 - intval($month);
			$monthArchive->write();
			$monthArchive->publish('Stage', 'Live');
			$monthArchive->flushCache();
		}

		// Return the archive
		return $monthArchive;
	}

	/**
	 * A simple test to check if an article from this archive is being displayed.
	 *
	 * @return boolean
	 */
	public function IsSection() {
		$url = preg_split('@/@', Controller::curr()->request->getVar('url'), null, PREG_SPLIT_NO_EMPTY);

		return isset($url[1]) && $url[1] == $this->URLSegment;
	}

	/**
	 * A simple test to check if this article archive is being displayed.
	 *
	 * @return boolean
	 */
	public function IsActive() {
		$url = preg_split('@/@', Controller::curr()->request->getVar('url'), null, PREG_SPLIT_NO_EMPTY);

		return count($url) == 2 && $url[1] == $this->URLSegment;
	}
}