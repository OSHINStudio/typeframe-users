<?php
/**
 * Base model for the user_reset table
 * This class was automatically generated from the database. Instead of
 * modifying it directly, extend it to add new functionality.
 */
class BaseModel_UserReset extends Dbi_Model {
	public function __construct() {
		parent::__construct();
		$this->name = 'user_reset';
		$this->prefix = DBI_PREFIX;
		$this->addField('userid', new Dbi_Field('int', array('10', 'unsigned'), '0', false));
		$this->addField('resetkey', new Dbi_Field('varchar', array('64'), '', false));
		$this->addField('expire', new Dbi_Field('datetime', array(), '0000-00-00 00:00:00', false));
		$this->addIndex('primary', array(
			'userid', 'resetkey'
		), 'unique');
	}
}
