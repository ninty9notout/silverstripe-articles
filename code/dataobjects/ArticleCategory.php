<?php
class ArticleCategory extends Category {
	static $belongs_many_many = array(
		'Articles' => 'Article'
	);

	public function URLPrefix() {
		return DataObject::get_one('ArticleHolder')->Link();
	}
}