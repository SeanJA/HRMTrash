<?php
$root  = dirname(__FILE__).'/';
$root = str_replace('tests'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'classes', '', $root);
define('ROOT', $root);
set_include_path(
	$root
	.PATH_SEPARATOR
	.ini_get("include_path")
);

require_once('PHPUnit/Framework.php');
require_once('config/config.php');
require_once('config/database.php');
require_once('config/user.php');
$config = (object)$config;
//require_once('library/shared.php');
require_once('library/classes/sroot.class.php');
require_once('library/classes/squerywrapper.class.php');
require_once('library/classes/smysqlquery.class.php');
require_once('library/classes/squery.class.php');

/**
 * Test class for sMYSQLQuery.
 * Generated by PHPUnit on 2009-06-19 at 13:45:05.
 */
class sQueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var    sMYSQLQuery
     * @access protected
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        $this->object = new sQuery;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
		$this->object->disconnect();
    }

    /**
     * 
     */
    public function testQueryDb()
    {
        // Remove the following lines when you implement this test.
		$this->assertEquals(
			array(),
			$this->object->queryDb("SELECT id, one_two_three FROM test WHERE id = 'asdf'")
		);
		$this->assertEquals(
			array(
				array
				(
					'id' => 1,
					'one_two_three' => 'one'
				)
			)
			,
			$this->object->queryDb("SELECT id, one_two_three FROM test WHERE id = 1")
		);
		$this->object->queryDb("UPDATE test SET one_two_three = 'two' WHERE id = 1");
		$this->assertEquals(
			array(
				array
				(
					'id' => 1,
					'one_two_three' => 'two'
				)
			)
			,
			$this->object->queryDb("SELECT id, one_two_three FROM test WHERE id = 1")
		);
		//revert the change
		$this->object->queryDb("UPDATE test SET one_two_three = 'one' WHERE id = 1");
		return;
		$this->assertEquals(
			array(
				array
				(
					'id' => 1,
					'value' => 'one'
				),
				array
				(
					'id' => 2,
					'value' => 'two'
				)
			)
			,
			$this->object->queryDb("SELECT id, one_two_three FROM test")
		);
    }

    /**
     * 
     */
    public function testGetNumRows()
    {
		$this->object->queryDb("SELECT * FROM test WHERE id = 'asdf'");
		$this->assertEquals(
			0,
			$this->object->getNumRows()
		);
		$this->object->queryDb("SELECT * FROM test WHERE id = 1");
		$this->assertEquals(
			1,
			$this->object->getNumRows()
		);
		$this->object->queryDb("SELECT * FROM test WHERE id IN (1, 2)");
		$this->assertEquals(
			2,
			$this->object->getNumRows()
		);
    }

    /**
     * 
     */
    public function testFrom()
    {
        $this->object->newQuery();
		$this->object->from('test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`",
			$select
		);

		$this->object->newQuery();
		$this->object->from('test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`",
			$select
		);

		$this->object->newQuery();
		$this->object->from('test', 'not_test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`, `not_test`",
			$select
		);

		$this->object->newQuery();
		$this->object->from('test');
		$this->object->from('not_test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`, `not_test`",
			$select
		);
    }

	public function testInto()
    {
        $this->object->newQuery();
		$this->object->into('test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`",
			$select
		);

		$this->object->newQuery();
		$this->object->into('test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`",
			$select
		);

		$this->object->newQuery();
		$this->object->into('test', 'not_test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`, `not_test`",
			$select
		);

		$this->object->newQuery();
		$this->object->into('test');
		$this->object->into('not_test');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test`, `not_test`",
			$select
		);
    }

    /**
     * 
     */
    public function testGroupBy()
    {
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->groupBy('id');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			'SELECT * FROM `test` GROUP BY `id`',
			$select
		);

		$this->object->newQuery();
		$this->object->from('test');
		$this->object->groupBy('id');
		$this->object->groupBy('value');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			'SELECT * FROM `test` GROUP BY `id`, `value`',
			$select
		);
		$this->object->groupBy('col3');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			'SELECT * FROM `test` GROUP BY `id`, `value`, `col3`',
			$select
		);
		
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->groupBy("value, id'");
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test` GROUP BY `value, id\'`",
			$select
		);
    }

    /**
     * 
     */
    public function testorderBy()
    {
        $this->object->newQuery();
		$this->object->from('test');
		$this->object->orderBy('id', 'ASC');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test` ORDER BY `id` ASC",
			$select
		);
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->orderBy('id', 'DESC');
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test` ORDER BY `id` DESC",
			$select
		);
    }

    /**
     * @todo Implement testlimit().
     */
    public function testlimit()
    {
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->limit(1);
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test` LIMIT 1",
			$select
		);
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->limit(1);
		$this->object->limit(2);
		$this->object->limit(3);
		$this->object->limit(4);
		$select = $this->object->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT * FROM `test` LIMIT 4",
			$select
		);
    }

    /**
     * @todo Implement testoffset().
     */
    public function testoffset()
    {
        $this->object->newQuery();
		$this->object->from('test');
		$this->object->offset(1);
		$select = $this->object->getSelect();
		$select = str_replace("\n", '', $select);
		$this->assertEquals(
			"SELECT * FROM `test` OFFSET 1",
			$select
		);
		$this->object->newQuery();
		$this->object->from('test');
		$this->object->offset(1);
		$this->object->offset(2);
		$this->object->offset(3);
		$this->object->offset(4);
		$select = $this->object->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT * FROM `test` OFFSET 4",
			$select
		);
    }

    /**
     * 
     */
    public function testColumn()
    {
		$select = $this->object->newQuery()
				->table('test')
				->column('value')
				->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT `value` FROM `test`",
			$select
		);

		$select = $this->object->newQuery()
				->table('test')
				->column('value')
				->column('one_two_three')
				->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT `value`, `one_two_three` FROM `test`",
			$select
		);

		$select = $this->object->newQuery()
				->table('test')
				->column('value')
				->column('one_two_three')
				->column('CONCAT(value,one_two_three)')
				->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT `value`, `one_two_three`, CONCAT(value,one_two_three) FROM `test`",
			$select
		);
		
		$this->object->newQuery();
		$select = $this->object->table('test')
				->column('value')
				->column('one_two_three')
				->column('CONCAT(value,one_two_three)')
				->column('IF(value, one_two_three, value) as if_test')
				->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT `value`, `one_two_three`, CONCAT(value,one_two_three), IF(value, one_two_three, value) as if_test FROM `test`",
			$select
		);

		$this->object->newQuery();
		$select = $this->object->table('test')
				->column('value')
				->column('one_two_three')
				->column('CONCAT(value,one_two_three)')
				->column('IF(value, one_two_three, value) as if_test')
				->column('NULL')
				->getSelect();
		$select = trim(str_replace("\n", '', $select));
		$this->assertEquals(
			"SELECT `value`, `one_two_three`, CONCAT(value,one_two_three), IF(value, one_two_three, value) as if_test, NULL FROM `test`",
			$select
		);
    }

    /**
     * @todo Implement testAddColumns().
     */
    public function testColumns()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddWhere().
     */
    public function testAddWhere()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddField().
     */
    public function testAddField()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddJoin().
     */
    public function testAddJoin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddLeftJoin().
     */
    public function testAddLeftJoin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddRightJoin().
     */
    public function testAddRightJoin()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSelectAll().
     */
    public function testSelectAll()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSelectRow().
     */
    public function testSelectRow()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSelectEnum().
     */
    public function testSelectEnum()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetSelect().
     */
    public function testGetSelect()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetDelete().
     */
    public function testGetDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testDelete().
     */
    public function testDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAffectedRows().
     */
    public function testAffectedRows()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetInsert().
     */
    public function testGetInsert()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetCount().
     */
    public function testGetCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetUpdate().
     */
    public function testGetUpdate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testTableFields().
     */
    public function testTableFields()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testDescribe().
     */
    public function testDescribe()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testInsert().
     */
    public function testInsert()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUpdate().
     */
    public function testUpdate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testLastInsertId().
     */
    public function testLastInsertId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testCount().
     */
    public function testCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
