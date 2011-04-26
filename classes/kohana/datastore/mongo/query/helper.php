<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Datastore_Mongo_Query_Helper {

	public static function compile_where_criteria(array $where)
	{
		$criteria = array();

		foreach ($where as $w)
		{
			list($field, $op, $value) = $w;

			switch($op)
			{
				case '=':
					$criteria[$field] = $value;
				break;
			}
		}

		return $criteria;
	}
}
