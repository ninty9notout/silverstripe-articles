<?php
class ArticlesSiteTreeDecorator extends DataExtension {
	/**
	 * Output the entire year and month archive list to filter articles by.
	 *
	 * @return HTMLText The rendered archive list
	 */
	public function ArticleArchive() {
		return $this->owner->customise(
			$this->ArticleYearArchive()
		)->renderWith('ArticleArchive');
	}

	/**
	 * Fetch all the year archive.
	 *
	 * @return DataList
	 */
	public function ArticleYearArchive() {
		return DataList::create('ArticleYearArchive')->sort('Title DESC');
	}

	/**
	 * Make the ArticleHolder available to all pages so the relevant archives can be listed from
	 * anywhere within the site.
	 *
	 * @return ArticleHolder
	 */
	public function ArticleHolder() {
		return DataObject::get_one('ArticleHolder', "ClassName = 'ArticleHolder'");
	}

	/**
	 * Output the type filter to view all the articles belongning to a specific type.
	 *
	 * @return HTMLText The rendered article type filter
	 */
	public function ArticleTypeFilter() {
		return $this->owner->customise(
			$this->ArticleTypes()
		)->renderWith('ArticleTypeFilter');
	}

	/**
	 * Return all the sub-classes there are of Article. These will be used to filter articles.
	 *
	 * @return ArrayList
	 */
	public function ArticleTypes() {
		$types = new ArrayList();

		// Get all sub-classes for Article and loop through them
		foreach(ClassInfo::subclassesFor('Article') as $subclass) {
			// Get an instance of the subclass
			$instance = singleton($subclass);
			
			// derp?
			if(!$instance->canCreate()) {
				continue;
			}
			
			// Make sure there are articles of this sub-class
			if(!DataObject::get($subclass)->count()) {
				continue;
			}

			// Create a slug to be used for the link
			$urlSegment = $instance->TypeURLSegment();

			// Determine if this is active based on the URL param and the article being displayed
			if(is_subclass_of($this->owner, 'Article')) {
				$active = $this->owner->TypeURLSegment() == $urlSegment;
			} elseif(Controller::curr()->request->getVar('type') == $urlSegment) {
				$active = true;
			} else {
				$active = false;
			}

			$types->add(array(
				// The singluar ClassName
				'Title' => $instance->i18n_singular_name(),
				// The plural ClassName
				'Plural' => $instance->i18n_plural_name(),
				// Generate a link using the slig generated above
				'Link' => Controller::curr()->Link('?type=' . $urlSegment),
				// The slug generated above
				'URLSegment' => $urlSegment,
				// If articles are already being filtered by this type
				'Active' => $active
			));
		}

		// Return the types array
		return $types;
	}

	/**
	 * Are the articles being filtered by a year or month archive?
	 *
	 * @return boolean
	 */
	public function HasActiveArchive() {
		return is_subclass_of($this->owner, 'ArticleHolder') || is_subclass_of($this->owner, 'Article');
	}
	
	/**
	 * Are the articles being filtered by type?
	 *
	 * @return boolean
	 */	
	public function HasActiveType() {
		return is_subclass_of($this->owner, 'Article') || !!Controller::curr()->request->getVar('type');
	}
}