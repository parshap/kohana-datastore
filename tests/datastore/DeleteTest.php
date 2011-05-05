<?php

class Datastore_DeleteTest extends Unittest_TestCase {

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

	public function provider_where()
	{
		return array(
			array(
				array(array('id', '=', 3)),
				array(1, 2, 4),
			),
			array(
				array(array('name', '=', 'u2')),
				array(1, 4),
			),
			array(
				array(array('age', '=', 20)),
				array(),
			),
		);
	}

	/**
	 * @dataProvider provider_where
	 */
	public function test_where($wheres, $expected_remaining_ids)
	{
		$query = self::$d->delete('users');

		foreach ($wheres as $where)
		{
			list($field, $operator, $operand) = $where;
			$query->where($field, $operator, $operand);
		}

		$query->execute();

		$ids = self::$d->retrieve('users')
			->execute()
			->as_array(NULL, 'id');

		$this->assertEquals(
			$expected_remaining_ids,
			array_intersect($expected_remaining_ids, $ids)
		);
	}
}
