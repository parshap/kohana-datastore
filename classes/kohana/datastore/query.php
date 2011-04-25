<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Query {

	protected $_type;

	public function __construct($type)
	{
		$this->_type = $type;
	}

	public abstract function reset();

	public abstract function execute($datastore = NULL);
}
