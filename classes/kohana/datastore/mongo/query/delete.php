<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Delete
	extends Datastore_Query_Delete {
	
	protected $_where_ops = array('=');

	public function execute($datastore = NULL)
	{
		$criteria = Datastore_Mongo_Query_Helper::compile_where_criteria(
			$this->_where
		);

		Datastore::instance($datastore)
			->collection($this->_what)
			->remove($criteria, array(
				'justOne' => FALSE,
			));
	}
}
