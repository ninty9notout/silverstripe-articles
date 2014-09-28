<?php
class ArticleHolder extends SectionLandingPage {
	static $allowed_children = array(
		'ArticleYearArchive',
		'Article'
	);

	static $default_child = 'ArticleYearArchive';
	
	static $db = array(
		'ListingSize' => 'Int'
	);

	static $defaults = array(
		'ListingSize' => 10
	);

	/**
	 * Check if a page with this class exists
	 *
	 * @param Member $member
	 * @return boolean True if page with this class exists
	 */
	public function canCreate($member = null) {
		return !DataObject::get_one($this->class);
	}

	/**
	 * Check if a this page can be deleted
	 *
	 * @param Member $member
	 * @return boolean False as this page shouldn't be deleted
	 */
	public function canDelete($member = null) {
		return false;
	}

	public function getSettingsFields() {
		$fields = parent::getSettingsFields();

		// Add a dropdown field to select how many articles to display
		$fields->addFieldToTab('Root.Settings', new DropdownField('ListingSize', 'Number of articles to display', array(
			5 => 5,
			10 => 10,
			15 => 15,
			20 => 20,
			25 => 25
		)));

		return $fields;
	}

	/**
	 * Check if an archive page for the specific year exists otherwise create it.
	 *
	 * @param int $year The year archive to look for
	 * @return ArticleYearArchive The existing or newly created year archive
	 */
	public function FindOrCreateYearArchive($year) {
		// First, try to find the year archive
		$yearArchive = DataObject::get_one(
			'ArticleYearArchive',
			sprintf("ParentID = %d AND URLSegment = '%s'", $this->ID, Convert::raw2sql($year))
		);

		// Create a new archive if there isn't an existing one
		if(!$yearArchive) {
			$yearArchive = new ArticleYearArchive();
			$yearArchive->Title = $year;
			$yearArchive->ParentID = $this->ID;
			$yearArchive->URLSegment = $year;
			$yearArchive->SortOrder = 2090 - intval($year);
			$yearArchive->write();
			$yearArchive->publish('Stage', 'Live');
			$yearArchive->flushCache();
		}

		// Return the archive
		return $yearArchive;
	}

	/**
	 * Creates an instance of this page after checking if one already exists
	 */
	public function requireDefaultRecords() {
		parent::requireDefaultRecords();

		if($page = DataObject::get_one('ArticleHolder')) {
			return false;
		}

		$page = new ArticleHolder();
		$page->Title = 'News';
		$page->Sort = 30;
		$page->write();
		$page->publish('Stage', 'Live');
		$page->flushCache();

		DB::alteration_message('Article Holder page created', 'created');
	}
}