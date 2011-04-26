<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Create
	extends Datastore_Query_Create {

	public function execute($datastore = NULL)
	{
		$object = $this->_set;

		Datastore::instance($datastore)
			->get_collection($this->_what)
			->insert($object);
	}
}
