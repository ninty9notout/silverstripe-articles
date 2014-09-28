<?php
class Article extends Page {
	/**
	 * Enable or disable main image
	 * @var bool
	 */
	static $enable_main_image = true;

	/**
	 * Enable or disable file, image, or video galleries
	 * @var bool
	 */
	static $enable_media = true;

	/**
	 * Enable or disable related content functionality
	 * @var bool
	 */
	static $enable_related_content = true;

	/**
	 * Enable or disable commenting functionality
	 * @var bool
	 */
	static $enable_comments = true;

	/**
	 * Enable or disable sitemap functionality (CMS only)
	 * @var bool
	 */
	static $enable_sitemap = true;

	static $db = array(
		'Tags' => 'Text',
		'Published' => 'SS_Datetime',
		'LastPublished' => 'SS_Datetime'
	);

	static $has_one = array(
		'Owner' => 'Member'
	);

	static $has_many = array(
		'RelatedArticles' => 'RelatedArticle'
	);

	static $many_many = array(
		'Categories' => 'ArticleCategory'
	);

	static $defaults = array(
		'ShowInMenus' => false,
		'ShowInSearch' => true,
		'ShowInSitemap' => true,
		'AllowComments' => true
	);

	static $schema = 'http://schema.org/Article';

	public function canCreate($member = null) {
		if(!$member) {
			$member = Member::currentUser();
		}

		return $member->inGroups(array('administrators', 'content-authors')) && get_class($this) !== 'Article';
	}

	public function canView($member = null) {
		if(!$member) {
			$member = Member::currentUser();
		}

		return $member->inGroups(array('administrators', 'content-authors'));
	}

	public function canEdit($member = null) {
		if(!$member) {
			$member = Member::currentUser();
		}

		return $member->inGroups(array('administrators', 'content-authors'));
	}

	public function canDelete($member = null) {
		if(!$member) {
			$member = Member::currentUser();
		}

		return $member->inGroups(array('administrators', 'content-authors'));
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', new TextField('Tags', 'Tags (comma sep.)'), 'Content');

		$fields->addFieldToTab('Root.Categories', new CheckboxSetField('Categories', 'Categories', DataObject::get('ArticleCategory')->map()));

		$fields->addFieldToTab('Root.RelatedContent', new GridField('RelatedArticles', 'Related Articles', $this->RelatedArticles(), GridFieldConfig_RecordEditor::create()));

		return $fields;
	}

	/**
	 * Need this to always validate to true so it can be used in template.
	 *
	 * @return boolean
	 */
	public function IsArticle() {
		return true;
	}

	/**
	 * As author is more common term, provide a synonym for Owner. Owner is more of a SilverStripe
	 * term and hence it's being used internally.
	 *
	 * @return Member The owner of this page
	 */
	public function Author() {
		return $this->Owner();
	}

	/**
	 * Get the previous article
	 *
	 * @return Article
	 */
	public function PreviousArticle() {
		$condition = sprintf("ParentID = %d AND Published < '%s'", $this->ParentID, $this->Published);

		return DataObject::get_one('Article', $condition, true, 'Published DESC');
	}
	
	/**
	 * Get the next article
	 *
	 * @return Article
	 */
	public function NextArticle() {
		$condition = sprintf("ParentID = %d AND Published > '%s'", $this->ParentID, $this->Published);

		return DataObject::get_one('Article', $condition, true, 'Published ASC');
	}

	/**
	 * Get the first category from the list of categories
	 *
	 * @return ArticleCategory
	 */
	public function Category() {
		return $this->Categories()->first();
	}

	/**
	 * Get the specific class name for this article. This is required as the Article class itself
	 * acts as an abstract and will never be used, and thus it is necessary to specify which type 
	 * of article a user is specifically reading.
	 *
	 * @return string The singular class name of the article
	 */
	public function Type() {
		return $this->i18n_singular_name();
	}

	/**
	 * Generate and get the link for the {@link Article->Type}
	 *
	 * @return string
	 */
	public function TypeLink() {
		return $this->ArticleHolder()->Link('?type=' . $this->TypeURLSegment());
	}

	/**
	 * Generate and get the URL segment for the {@link Article->Type}
	 *
	 * @return string
	 */
	public function TypeURLSegment() {
		return URLSegmentFilter::create()->filter($this->singular_name());
	}

	/**
	 * Get the Schema type for this article. Uses a static which should ideally be overridden by
	 * any sub-classes of this class, but not necessary, unless it's a job vacancy. See 
	 * {@link http://schema.org} for more details.
	 *
	 * @return string
	 */
	public function Schema() {
		return static::$schema;
	}

	protected function onBeforeWrite() {
		parent::onBeforeWrite();

		// Set default owner
		if(!$this->OwnerID) {
			$this->OwnerID = Member::currentUser() ? Member::currentUser()->ID : 0;
		}

		// Fetch or create year page
		if(!$this->ID || !$this->Published) {
			$year = SS_Datetime::now()->Format('Y');
		} else {
			$year = $this->obj('Published')->Format('Y');
		}

		$yearArchive = $this->ArticleHolder()->FindOrCreateYearArchive($year);

		// Fetch or create month page
		if(!$this->ID || !$this->Published) {
			$month = SS_Datetime::now()->Format('m');
		} else {
			$month = $this->obj('Published')->Format('m');
		}

		$monthArchive = $yearArchive->FindOrCreateMonthArchive($month);

		// Set parent page to month page
		$this->ParentID = $monthArchive->ID;
	}

	public function onBeforePublish() {
		// Save the datetime if this is the first time this article is being published
		if(!$this->record['ID'] || !$this->Published) {
			$this->Published = SS_Datetime::now()->Rfc2822();
		}

		// Save the current datetime
		$this->LastPublished = SS_Datetime::now()->Rfc2822();
	}
}

class BlogPost extends Article {
	static $schema = 'http://schema.org/BlogPosting';
}

class NewsArticle extends Article {
	static $schema = 'http://schema.org/NewsArticle';
}

class PressRelease extends Article { }