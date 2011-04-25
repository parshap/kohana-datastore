<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Where extends Datastore_Query {

	protected $_criteria = array();

	public function where($field, $op, $value)
	{
		return $this->and_where($field, $op, $value);
	}

	public function and_where($field, $op, $value)
	{
		$this->_criteria[] = array($field, $op, $value);

		return $this;
	}
}
