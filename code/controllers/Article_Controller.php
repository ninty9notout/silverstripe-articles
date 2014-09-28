<?php
class Article_Controller extends Page_Controller {
	/**
	 * Output a list of articles that are selected to be related to this article
	 *
	 * @return mixed The rendered related articles or false
	 */
	public function RelatedArticles() {
		if(!$this->dataRecord->RelatedArticles()->count()) {
			return false;
		}

		$related = new ArrayList();

		foreach($this->dataRecord->RelatedArticles() as $article) {
			$related->add($article->LinkTo());
		}

		return $this->customise(array(
			'RelatedArticles' => $related
		))->renderWith('RelatedArticles');
	}

	/**
	 * Output the next and previous article links for the this article
	 *
	 * @return mixed The rendered sibling article links or false
	 */
	public function SiblingArticles() {
		if(!$this->dataRecord->PreviousArticle() && !$this->dataRecord->NextArticle()) {
			return false;
		}

		return $this->customise(array(
			'PreviousArticle' => $this->dataRecord->PreviousArticle(),
			'NextArticle' => $this->dataRecord->NextArticle()
		))->renderWith('SiblingArticles');
	}
}

class BlogPost_Controller extends Article_Controller { }

class NewsArticle_Controller extends Article_Controller { }

class JobVacancy_Controller extends Article_Controller { }

class PressRelease_Controller extends Article_Controller { }