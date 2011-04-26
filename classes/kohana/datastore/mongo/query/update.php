<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Update
	extends Datastore_Query_Update {

	public function execute($datastore = NULL)
	{
		$criteria = Datastore_Mongo_Query_Helper::_compile_where_criteria(
			$this->_where
		);

		$object = array(
			'$set' => $this->_set,
		);

		Datastore_Mongo::instance($datastore)
			->get_collection($this->_what)
			->update($criteria, $object, array(
				'upsert' => FALSE,
				'multiple' => TRUE,
			));
	}
}