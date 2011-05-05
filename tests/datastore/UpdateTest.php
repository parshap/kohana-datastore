<?php

class Datastore_UpdateTest extends Unittest_TestCase {

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
				array(array('id', '=', 1)),
				array('name' => 'user1')
			),
		);
	}

	/**
	 * @dataProvider provider_where
	 */
	public function test_where($wheres, $sets)
	{
		// Get current state of objects.
		$objects_old = self::$d->retrieve('users')
			->execute()
			->as_array('id');

		// Figure out what IDs are going to change.
		$query = self::$d->retrieve('users');

		foreach ($wheres as $where)
		{
			list($field, $operator, $operand) = $where;
			$query->where($field, $operator, $operand);
		}

		$changed_ids = $query->execute()
			->as_array(NULL, 'id');

		// Do the update
		$query = self::$d->update('users')
			->set($sets);

		foreach ($wheres as $where)
		{
			list($field, $operator, $operand) = $where;
			$query->where($field, $operator, $operand);
		}

		$query->execute();

		// Get new state of objects.
		$objects_new = self::$d->retrieve('users')
			->execute()
			->as_array('id');

		// Compare the before and after states of objects.
		
		// Make sure the same objects were retrieved.
		$this->assertEquals(
			array_keys($objects_old),
			array_intersect(array_keys($objects_old), array_keys($objects_new))
		);

		foreach ($objects_new as $id => $object)
		{
			if (in_array($id, $changed_ids))
			{
				// This object was updated, make sure the change persisted
				foreach ($sets as $field => $value)
				{
					$this->assertEquals($object[$field], $value);
				}
			}
			else
			{
				// The object was not updated, make sure it's the same.
				$this->assertEquals($objects_old[$id], $objects_new[$id]);
			}
		}
	}
}
