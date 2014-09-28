<?php
class ArticleHolder_Controller extends Page_Controller {
	public function init() {
		parent::init();

		// Add an RSS feed link in the header for devices that support this
		Requirements::insertHeadTags(sprintf('<link rel="alternate" type="application/rss+xml" title="%s" href="%s">', Convert::raw2xml($this->SiteConfig()->Title . " - " . $this->Title), $this->Link('rss')));
	}

	/**
	 * Output the articles with pagination (if required)
	 *
	 * return HTMLText The rendered list of articles
	 */
	public function ArticlesWithPagination() {
		// How many articles to display?
		$limit = $this->dataRecord->ListingSize ?: $this->dataRecord->ArticleHolder()->ListingSize;

		// Get the current page if it's set, otherwise use 1
		$page = !is_null($this->request->getVar('p')) ? $this->request->getVar('p') : 1;

		// Workout the offset
		$offset = ($page - 1) * $limit;

		// Fetch all articles if this is the article holder page itself
		if(!is_subclass_of($this, 'ArticleHolder_Controller')) {
			$articles = DataList::create('Article')
				->sort('Published DESC');
		} else {
			// Otherwise just fetch the specific ones to the current archive
			$articles = $this->dataRecord->Articles();
		}

		// Filter by type if one is set
		if($this->HasActiveType()) {
			// Get all sub-classes for Article and loop through them
			foreach(ClassInfo::subclassesFor('Article') as $subclass) {
				// Get an instance of the subclass
				$instance = singleton($subclass);

				if($instance->TypeURLSegment() == $this->request->getVar('type')) {
					$articles = $articles->where(sprintf("ClassName = '%s'", $instance->ClassName));
				}
			}
		}

		// Count how mnay records there are BEFORE applying the page limit
		$total = $articles->count();

		// Work out how many pages are needed to list all the articles
		$pages = ceil($total / $limit);

		return $this->customise(array(
			// Apply the offset and limit to the articles
			'Articles' => $articles->limit($limit, $offset),
			// Generate the pagination
			'Pagination' => $this->Pagination($page, $pages)
		))->renderWith('ArticlesWithPagination');
	}

	/**
	 * Generate the pagination if there are more than one page
	 *
	 * @param int $currentPage Page currently being displayed
	 * @param int $totalPages Total pages required to list all the articles
	 * @return mixed The generated pagination or false
	 */
	public function Pagination($currentPage, $totalPages) {
		// No pagination is required if there is only one page
		if($totalPages < 2) {
			return false;
		}

		// Open the container tag
		$out = '<ul>';

		// Make the previous link disabled if the first page is being displayed
		if($currentPage == 1) {
			$out.= '<li class="disabled"><a>Previous</a></li>';
		} else {
			$out.= sprintf('<li><a href="%s">Previous</a></li>', $this->Link('?p=' . ($currentPage - 1)));
		}

		// Make the next link disabled if the last page is being displayed
		if($currentPage == $totalPages) {
			$out.= '<li class="disabled"><a>Next</a></li>';
		} else {
			$out.= sprintf('<li><a href="%s">Next</a></li>', $this->Link('?p=' . ($currentPage + 1)));
		}

		// Generate the page links
		for($i = 1; $i <= $totalPages; $i++) {
			// Add the active class for the current page
			if($i == $currentPage) {
				$out.= sprintf('<li class="active"><a>%d</a></li>', $i);
			} else {
				$out.= sprintf('<li><a href="%s">%d</a></li>', $this->Link('?p=' . $i), $i);
			}
		}

		// Close the container tag and return the container
		return $out . '</ul>';
	}

	/**
	 * Output the articles in the RSS format
	 *
	 * @return string The formatted RSS feed
	 */
	public function rss(SS_HTTPRequest $request) {
		// Fetch the first 10 articles after sorting by publish date descending
		$articles = DataList::create('Article')
			->sort('Published DESC')
			->limit(10);

		// Add some meta to the RSS feed
		$rss = new RSSFeed($articles, $this->Link(), $this->Title, $this->obj('Content')->Summary(), 'Title', 'Content');

		// Output the feed to browser
		return $rss->outputToBrowser();
	}
}

class ArticleYearArchive_Controller extends ArticleHolder_Controller { }

class ArticleMonthArchive_Controller extends ArticleHolder_Controller { }