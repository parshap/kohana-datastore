<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore {
	
	public static $instances = array();

	public static $default = 'default';

	protected $_instance;

	protected $_config;

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
		if ( ! isset(Datastore::$instance[$name]))
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
}
