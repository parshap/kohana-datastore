<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query_Delete extends Datastore_Query_Where {

	public function __construct($thing)
	{
		parent::__construct(Datastore::DELETE, $thing);
	}
}
