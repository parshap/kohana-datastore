<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Create extends Datastore_Query {

	protected $_set = array();

	public function __construct($thing)
	{
		parent::__construct(Datastore::CREATE, $thing);
	}

	public function set(array $values)
	{
		$this->_set = array_merge($this->_set, $values);

		return $this;
	}

	public function reset()
	{
		$this->_what = NULL;
		$this->values = array();
	}
}
