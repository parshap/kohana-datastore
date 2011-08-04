<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo extends Kohana_Datastore {

	// Server connection
	protected $_connection;

	// Database connection
	protected $_db;

	public function connect()
	{
		if ($this->connected())
		{
			return;
		}
		// Extract the connection parameters, adding required variables
		/**
		 * $config['connection'] = array(
		 *	'host' =>
		 *	'username' =>
		 *	'password' =>
		 *	'database' =>
		 *	'options' => 
		 * );
		 */
		extract($this->_config['connection'] + array(
			'host' => 'localhost:27017',
			'options' => array(),
		));

		// Clear connection info for security
		unset($this->_config['connection']);

		if ( ! isset($database))
		{
			throw new Kohana_Exception('No database specified');
		}

		// Create the connection string
		$cs = 'mongodb://';

		if ( ! empty($username) && ! empty($password))
		{
			$cs .= $username.':'.$password.'@';
		}

		$cs .= $host;

		// Don't connect in the constructor.
		$options['connect'] = FALSE;

		$this->_connection = new Mongo($cs, $options);

		try
		{
			$this->_connection->connect();
		}
		catch ( MongoConnectionException $e)
		{
			// Unable to connect to the database server
			throw new Kohana_Exception(
				'Unable to connect to MongoDB server at :host',
				array(':host' => $e->getMessage())
			);
		}

		$this->_db = $this->_connection->selectDB($database);

		return TRUE;
	}

	public function disconnect()
	{
		if ($this->_connection)
		{
			$this->_connection->close();
		}

		$this->_db = $this->_connection = NULL;
	}

	public function connected()
	{
		return $this->_connection && $this->_connection->connected;
	}

	public function list_dbs()
	{
		return $this->_connection->listDBs();
	}

	public function command(array $command)
	{
		return $this->_db->selectCollection('$cmd')->findOne($command);
	}

	public function execute($code, array $args = array())
	{
		$this->command(array(
			'$eval' => $code,
			'args' => $args
		));
	}

	public function collection($name)
	{
		// Ensure a connection
		$this->connected() or $this->connect();

		return $this->_db->selectCollection($name);
	}

	public function create_collection()
	{
		// @todo
	}

	public function drop()
	{
		// @todo
	}

	public function last_error()
	{
		// @todo
	}

	public function prev_error()
	{
		// @todo
	}

	public function reset_error()
	{
		// @todo
	}

	public function list_collections()
	{
		// @todo
	}

	/**
	 * Tries to return a MongoCollection if the property does not exist.
	 */
	public function __get($name)
	{
		return $this->collection($name);
	}
}
