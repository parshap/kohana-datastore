<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore {
	
	public static $instances = array();

	public static $default = 'default';

	protected $_instance;

	protected $_config;

	const CREATE = 1;
	const RETRIEVE = 2;
	const UPDATE = 3;
	const DELETE = 4;

	public function __construct($name, array $config)
	{
		$this->_instance = $name;
		$this->_config = $config;

		Datastore::$instances[$name] = $this;
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	public abstract function connect();

	public abstract function disconnect();

	public static function instance($name = NULL, array $config = NULL)
	{
		if ($name === NULL)
		{
			// User the default instance name
			$name = Datastore::$default;
		}

		// If the instance doesn't already exist, create it
		if ( ! isset(Datastore::$instances[$name]))
		{
			if ($config === NULL)
			{
				$config = Kohana::config('datastore')->$name;
			}

			if ( ! isset($config['type']))
			{
				throw new Kohana_Exception(
					'Datastore type not defined in :name config',
					array(':name' => $name)
				);
			}

			$datastore = 'Datastore_'.ucfirst($config['type']);

			new $datastore($name, $config);
		}

		return Datastore::$instances[$name];
	}

	protected function _get_query_object($operation, $thing)
	{
		$class = 'Datastore_'.$this->_config['type'].'_Query_'.$operation;
		return new $class($thing);
	}

	public function __call($name, $arguments)
	{
		static $query_operations = array('create', 'update', 'retrieve', 'delete');

		if (in_array(strtolower($name), $query_operations))
		{
			return $this->_get_query_object($name, $arguments[0]);
		}
	}
}
