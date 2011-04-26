<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Update extends Datastore_Query_Where {

	protected $_set = array();

	public function __construct($thing)
	{
		parent::__construct(Datastore::UPDATE, $thing);
	}

	public function set(array $values)
	{
		$this->_set = array_merge($this->_set, $values);

		return $this;
	}
}
