<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Result extends Datastore_Result {

	protected $_current_position = 0;

	public function __construct($cursor)
	{
		// Put the corsor in the "post-query stage"
		$cursor->next();

		parent::__construct($cursor);
	}

	/* Countable methods */
	public function count()
	{
		return $this->_result->count();
	}

	/* Iterator methods */
	public function current()
	{
		return $this->_result->current();
	}

	public function key()
	{
		return $this->_current_position;
	}

	public function next()
	{
		$this->_current_position += 1;

		$this->_result->next();
	}

	public function rewind()
	{
		$this->_current_position = 0;

		$this->_result->rewind();
	}

	public function valid()
	{
		return $this->_result->valid();
	}

	/* SeekableIterator methods */
	public function seek($offset)
	{
		// @todo: this is expensive
		$this->_result->reset();
		$this->_result->skip($offset);
		$this->_current_position = $offset;
		$this->_result->next();
	}
}
