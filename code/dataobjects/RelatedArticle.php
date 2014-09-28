<?php
class RelatedArticle extends DataObject {
	static $extensions = array(
		'RelatedLink'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->addFieldToTab('Root.Main', new DropdownField('LinkToID', 'Article', DataObject::get('Article')->map('ID', 'Title')));

		return $fields;
	}
}