<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Result
	implements Countable, Iterator, SeekableIterator, ArrayAccess {
	
	// Result resource
	protected $_result;

	public function __construct($result)
	{
		$this->_result = $result;
	}

	public function as_array($key = NULL, $value = NULL)
	{
		$result = array();

		if ($key === NULL AND $value === NULL)
		{
			// Return an array containing each result row in its entirety.
			foreach ($this as $row)
			{
				$result[] = $row;
			}
		}

		elseif ($value === NULL)
		{
			// Array containing each row with $key as keys.
			foreach ($this as $row)
			{
				$result[$row[$key]] = $row;
			}
		}

		elseif ($key === NULL)
		{
			// Array containing $row[$value] for each row
			foreach ($this as $row)
			{
				$result[] = $row[$value];
			}
		}

		else
		{
			// Array containing $row[$key] => $row[$value] pairs
			foreach ($this as $row)
			{
				$result[$row[$key]] = $row[$value];
			}
		}

		return $result;
	}

	public function get($field, $default = NULL)
	{
		$row = $this->current();
		return isset($row[$field]) ? $row[$field] : $default;
	}
	
	/* Countable methods */
	// abstract public function count();

	/* Iterator methods */
	// abstract public function current();

	// abstract public function key();

	// abstract public function next();

	// abstract public function rewind();

	// abstract public function valid();

	/* SeekableIterator methods */
	// abstract public function seek($offset);

	/* ArrayAccess methods */
	public function offsetExists($offset)
	{
		return $offset >= 0 AND $offset < $this->count();
	}

	public function offsetGet($offset)
	{
		if ( ! $this->offsetExists($offset))
		{
			return NULL;
		}

		$this->seek($offset);

		return $this->valid() ? $this->current() : NULL;
	}

	final public function offsetSet($offset, $value)
	{
		throw new Kohana_Exception('Datastore results are read-only');
	}

	final public function offsetUnset($offset)
	{
		throw new Kohana_Exception('Datastore results are read-only');
	}
}
