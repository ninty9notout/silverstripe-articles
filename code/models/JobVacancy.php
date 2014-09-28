<?php
class JobVacancy extends Article {
	static $db = array(
		'JobTitle' => 'Varchar(255)',
		'JobType' => 'Enum(array("Full Time", "Part Time", "Temporary", "Contract", "Internship"))',
		'Location' => 'Varchar(255)',
		'Hours' => 'Text',
		'Salary' => 'Text',
		'Qualifications' => 'Text',
		'Skills' => 'Text',
		'ClostingDate' => 'SS_Datetime'
	);

	static $has_one = array(
		// 'HiringOrganisation' => 'Organisation'
	);

	static $defaults = array(
		'Full Time'
	);

	static $schema = 'http://schema.org/JobPosting';

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldsToTab('Root.JobDetails', array(
			new TextField('JobTitle', 'Job Title'),
			new DropdownField('JobType', 'Type', singleton('JobVacancy')->dbObject('JobType')->enumValues()),
			new TextField('Location'),
			new TextareaField('Hours'),
			new TextareaField('Salary'),
			new TextareaField('Qualifications'),
			new TextareaField('Skills'),
			new DatetimeField('ClostingDate', 'Closing Date')
		));

		return $fields;
	}
}