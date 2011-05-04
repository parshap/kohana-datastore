<?php

class Datastore_RetrieveTest extends Unittest_TestCase {

	protected static $d = NULL;

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		$seed = array(
			array(
				'id' => 1,
				'name' => 'u1',
				'age' => 20,
			),
			array(
				'id' => 2,
				'name' => 'u2',
				'age' => 24,
			),
			array(
				'id' => 3,
				'name' => 'u3',
				'age' => 20,
			),
			array(
				'id' => 4,
				'name' => 'u4',
				'age' => 20,
			),
		);

		self::$d = Datastore::instance();

		self::$d->collection('users')->drop();

		foreach ($seed as $user)
		{
			self::$d->create('users')
				->set($user)
				->execute();
		}
	}

	public function provider_fields()
	{
		return array(
			array(array(), array('id', 'name', 'age'), array()),
			array(array('name'), array('name'), array('id', 'age')),
			array(array('name', 'age'), array('name', 'age'), array('id')),
		);
	}

	/**
	 * @dataProvider provider_fields
	 */
	public function test_fields($fields, $expected_fields, $not_expected_fields)
	{
		$first = self::$d->retrieve('users')
			->fields_array($fields)
			->execute()
			->current();

		$this->assertEquals(
			$expected_fields,
			array_intersect($expected_fields, array_keys($first))
		);

		$this->assertEquals(
			array(),
			array_intersect($not_expected_fields, array_keys($first))
		);
	}

	public function provider_limit_offset()
	{
		return array(
			array(NULL, NULL, array(1, 2, 3, 4)),
			array(1, NULL, array(1)),
			array(1, 1, array(2)),
			array(2, 2, array(3, 4)),
		);
	}

	/**
	 * @dataProvider provider_limit_offset
	 */
	public function test_limit_offset($limit, $offset, $expected_ids)
	{
		$ids = self::$d->retrieve('users')
			->limit($limit)
			->offset($offset)
			->execute()
			->as_array(NULL, 'id');

		$this->assertEquals($expected_ids, $ids);
	}

	public function provider_where()
	{
		return array(
			array(
				array(),
				array(1, 2, 3, 4),
			),
			array(
				array(array('name', '=', 'u1')),
				array(1),
			),
			array(
				array(array('name', '=', '__invalid__')),
				array(),
			),
			array(
				array(
					array('name', '=', 'u4'),
					array('age', '=', 20)
				),
				array(4),
			),
		);
	}

	/**
	 * @dataProvider provider_where
	 */
	public function test_where($wheres, $expected_ids)
	{
		$query = self::$d->retrieve('users');

		foreach ($wheres as $where)
		{
			list($field, $operator, $operand) = $where;
			$query->where($field, $operator, $operand);
		}

		$result = $query->execute();
		$ids = $result->as_array(NULL, 'id');

		$this->assertEquals($expected_ids, $ids);
	}

	public function provider_sort()
	{
		return array(
			array(
				array('index' => Datastore::ASCENDING),
				array(1, 2, 3, 4),
			),
			array(
				array('index' => Datastore::DESCENDING),
				array(1, 2, 3, 4),
			),
			array(
				array(
					'index' => Datastore::DESCENDING
				),
				array(1, 2, 3, 4),
			),
		);
	}

	/**
	 * @dataProvider provider_sort
	 */
	public function test_sort($sorts, $expected_ids)
	{
		$query = self::$d->retrieve('users');

		foreach ($sorts as $field => $direction)
		{
			$query->sort($field, $direction);
		}

		$result = $query->execute();
		$ids = $result->as_array(NULL, 'id');

		$this->assertEquals($expected_ids, $ids);
	}
}
