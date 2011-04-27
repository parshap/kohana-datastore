<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query {

	protected $_type;

	// The thing that this query operates on (e.g., "users")
	protected $_what;

	public function __construct($type, $thing)
	{
		$this->_type = $type;
		$this->_what = $thing;
	}

	public abstract function reset();

	public abstract function execute($datastore = NULL);
}
