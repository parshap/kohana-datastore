<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Retrieve extends Datastore_Query_Where {

	/**
	 * @var Fields to be returned by the retrieve.
	 */
	protected $_fields;

	protected $_offset;

	protected $_sort;

	public function __construct($thing)
	{
		parent::__construct(Datastore::RETRIEVE, $thing);
	}

	public function fields($fields = NULL)
	{
		return $this->fields_array(func_get_args());
	}

	public function fields_array($fields = NULL)
	{
		$this->_fields = array_merge($this->_fields, $fields);

		return $this;
	}

	public function offset($number)
	{
		$this->_offset = (int) $number;

		return $this;
	}

	public function sort($field, $direction = 1)
	{
		$this->_sort[$field] = $direction === 1 ? 1 : -1;

		return $this;
	}

	public function reset()
	{
		$this->_offset =
		$this->_fields = NULL;

		$this->_sort = array();

		return parent::reset();
	}
}
