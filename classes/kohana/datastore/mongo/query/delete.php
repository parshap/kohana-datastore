<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Delete
	extends Datastore_Query_Delete {
	
	public function execute($datastore = NULL)
	{
		$criteria = Datastore_Mongo_Query_Helper::_compile_where_criteria(
			$this->_where
		);

		Datastore::instance($datastore)
			->remove($criteria, array(
				'justOne' => FALSE,
			));
	}
}
