<?php

class Datastore_ResultTest extends Unittest_TestCase {

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

	private function _get_result()
	{
		return self::$d->retrieve('users')
			->sort('id', Datastore::ASCENDING)
			->execute();
	}

	public function test_iteration()
	{
		$result = $this->_get_result();

		foreach ($result as $key => $row)
		{
		}
	}

	public function test_count()
	{
		$result = $this->_get_result();

		$this->assertEquals(4, $result->count());
	}

	public function test_as_array_empty()
	{
		$result = $this->_get_result();
		$array = $result->as_array();

		$this->assertInternalType('array', $array);
		$this->assertEquals(4, count($array));
		$this->assertEquals(array(0, 1, 2, 3), array_keys($array));
	}

	public function provider_as_array()
	{
		return array(
			array(NULL, 'id', array(1, 2, 3, 4)),
			array('id', 'name', array(1 => 'u1', 2 => 'u2', 3 => 'u3', 4 => 'u4')),
		);
	}

	/**
	 * @dataProvider provider_as_array
	 */
	public function test_as_array($key, $value, $expected)
	{
		$result = $this->_get_result();

		$array = $result->as_array($key, $value);

		$this->assertEquals($expected, $array);
	}

	public function provider_get()
	{
		return array(
			array('id', 1),
			array('name', 'u1'),
		);
	}

	/**
	 * @dataProvider provider_get
	 */
	public function test_get($field, $expected)
	{
		$result = $this->_get_result();
		$get = $result->get($field);

		$this->assertEquals($expected, $get);
	}

	public function test_current()
	{
		$result = $this->_get_result();

		$this->assertTrue((bool) $result->current());

		$result->seek(4);
		$this->assertFalse((bool) $result->current());
	}

	public function test_key()
	{
		$result = $this->_get_result();

		$this->assertEquals(0, $result->key());

		$result->seek(2);
		$this->assertEquals(2, $result->key());

		$result->next();
		$this->assertEquals(3, $result->key());

		$result->rewind();
		$this->assertEquals(0, $result->key());
	}

	public function test_next()
	{
		$result = $this->_get_result();

		$result->next();
		$c = $result->current();
		$this->assertEquals(2, $c['id']);

		$result->next();
		$c = $result->current();
		$this->assertEquals(3, $c['id']);
	}

	public function test_rewind()
	{
		$result = $this->_get_result();

		$result->next();
		$this->assertNotEquals(0, $result->key());
		$this->assertNotEquals(1, $result->get('id'));

		$result->rewind();
		$this->assertEquals(0, $result->key());
		$this->assertEquals(1, $result->get('id'));
	}


	public function test_valid()
	{
		$result = $this->_get_result();

		$this->assertTrue($result->valid());

		$result->seek(4);
		$this->assertFalse($result->valid());
	}

	public function test_offsetExists()
	{
		$result = $this->_get_result();

		$this->assertTrue($result->offsetExists(0));
		$this->assertTrue($result->offsetExists(2));
		$this->assertTrue($result->offsetExists(3));
		$this->assertFalse($result->offsetExists(4));
		$this->assertFalse($result->offsetExists(-1));
	}

	public function provider_offsetGet()
	{
		return array(
			array(0, 1),
			array(1, 2),
			array(3, 4),
		);
	}

	/**
	 * @dataProvider provider_offsetGet
	 */
	public function test_offsetGet($offset, $expected_id)
	{
		$result = $this->_get_result();

		$r0 = $result->offsetGet($offset);
		$this->assertEquals($expected_id, $r0['id']);
	}

	public function test_offsetGet_invalid()
	{
		$result = $this->_get_result();

		$this->assertNull($result->offsetGet(4));
		$this->assertNull($result->offsetGet(-1));
	}

	public function test_offsetSet()
	{
		$this->setExpectedException('Kohana_Exception');

		$result = $this->_get_result();
		$result->offsetSet(0, NULL);
	}

	public function test_offsetUnset()
	{
		$this->setExpectedException('Kohana_Exception');

		$result = $this->_get_result();
		$result->offsetUnset(0);
	}
}
