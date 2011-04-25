<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Datastore_Query_Create extends Datastore_Query {

	protected $_values = array();

	protected $_what;

	public function __construct($what)
	{
		$this->_what = $what;
	}

	public function values(array $values)
	{
		$this->_values = array_merge($this->_values, $values);
	}

	public function reset()
	{
		$this->_what = NULL;
		$this->values = array();
	}
}
