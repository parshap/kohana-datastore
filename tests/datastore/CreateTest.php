<?php

class Datastore_CreateTest extends Unittest_TestCase {

	protected static $d = NULL;

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		self::$d = Datastore::instance();
		self::$d->collection('users')->drop();
	}

	public function test_create()
	{
		$id = self::$d->create('users')
			->set(array('foo' => 'bar'))
			->execute();

		$this->assertTrue((bool) $id);

		$results = self::$d->retrieve('users')
			->where('foo', '=', 'bar')
			->execute();

		$this->assertEquals(1, count($results));
	}
}
