<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Where extends Datastore_Query {

	protected $_where = array();

	protected $_where_ops = array();

	public function where($field, $op, $value)
	{
		return $this->and_where($field, $op, $value);
	}

	public function and_where($field, $op, $value)
	{
		// Make sure the operator is supported.
		if ( ! in_array($op, $this->_where_ops))
		{
			throw new Kohana_Exception(
				'Where operator, :op, not supported by :class',
				array(
					':op' => $op,
					':class' => get_class($this),
				)
			);
		}

		$this->_where[] = array($field, $op, $value);

		return $this;
	}

	public function reset()
	{
		$this->_where = array();

		return $this;
	}
}
