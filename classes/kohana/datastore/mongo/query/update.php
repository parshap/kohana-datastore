<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Update
	extends Datastore_Query_Update {

	protected $_replace = FALSE;

	public function replace(bool $replace)
	{
		$this->_replace = $replace;
	}

	public function execute($datastore = NULL)
	{
		$criteria = Datastore_Mongo_Query_Helper::_compile_where_criteria(
			$this->_where
		);

		$object = $this->_replace
			? $this->_set
			: array('$set' => $this->_set);

		Datastore_Mongo::instance($datastore)
			->get_collection($this->_what)
			->update($criteria, $object, array(
				'upsert' => FALSE,
				'multiple' => TRUE,
			));
	}

	public function reset()
	{
		$this->_replace = FALSE;

		parent::reset();
	}
}
