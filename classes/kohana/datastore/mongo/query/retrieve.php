<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Retrieve
	extends Datastore_Query_Retrieve {

	protected $_where_ops = array('=');

	public function execute($datastore = NULL)
	{
		$criteria = Datastore_Mongo_Query_Helper::_compile_where_criteria(
			$this->_where
		);

		$cursor = Datastore_Mongo::instance($datastore)
			->get_collection($this->_what)
			->find($criteria);

		if ($this->_fields !== NULL)
		{
			$cursor->fields($this->_fields);
		}

		if ($this->_limit !== NULL)
		{
			$cursor->limit($this->_limit);
		}

		if ($this->_offset !== NULL)
		{
			$cursor->skip($this->_offset);
		}

		if ($this->_sort)
		{
			$cursor->sort($this->_sort);
		}

		return $cursor;
	}
}
