<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Helper {

	public static $_where_ops = array(
		'=',
		'!=',
		'>',
		'>=',
		'<',
		'<=',
		'exists',
		'in',
	);

	protected static $_mongo_ops = array(
		'!=' => '$ne',
		'>' => '$gt',
		'>=' => '$gte',
		'<' => '$lt',
		'<=' => '$lte',
		'exists' => '$exists',
		'in' => '$in',
	);

	public static function compile_where_criteria(array $where)
	{
		$criteria = array();

		foreach ($where as $w)
		{
			list($field, $op, $value) = $w;

			// Default this field's criteria to an array so we can simply add
			// any advanced operators to it. Using the equality operator will
			// overwrite the array value and can cause confusing inconsistent
			// behavior depending on the order of the where() calls.
			if ( ! array_key_exists($field, $criteria))
			{
				$criteria[$field] = array();
			}

			// @todo: Throw an error if an equality operator is mixed with
			// advanced operator operators?

			switch($op)
			{
				case '=':
					$criteria[$field] = $value;
				break;

				case '!=':
				case '>':
				case '>=':
				case '<':
				case '<=':
				case 'exists':
				case 'in':
					$mongo_op = self::$_mongo_ops[$op];
					$criteria[$field] += array($mongo_op => $value);
				break;
			}
		}

		return $criteria;
	}
}
