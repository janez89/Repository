<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 10. 08.
 * Time: 9:56
 */

use Illuminate\Database\Capsule\Manager as DB;

class DataTableTest extends FunctionalTestCase
{
    protected $count = 10;
    public function setUp()
    {
        parent::setUp();

        $this->migrateTable();
        $this->seedData();
    }

    public function migrateTable()
    {
        DB::Schema()->create('tests', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('seq');
            $table->boolean('flag')->nullable();
            $table->timestamps();
        });
    }

    protected function seedData()
    {
        $faker = $this->getFaker();

        for($i=1; $i <= $this->count; $i++) {
            DB::table('tests')->insert([
                'title' => $faker->title,
                'seq' => $i,
                'created_at' => $i > 5 ?
                    (new DateTime('2005-01-01'))->format('Y-m-d H:i:s') :
                    (new DateTime('1998-01-01'))->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime(),
            ]);
        }
    }

    protected function getRequestMock($start, $length, $draw)
    {
        $requestMock = $this->getMockBuilder('\Illuminate\Support\Facades\Request')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                ['start', $start],
                ['length', $length],
                ['draw', $draw],
            ]));

        return $requestMock;
    }

    // --- TESTS -------------------------

    public function testInputData()
    {
        $mock = $this->getRequestMock(3,4,5);

        $this->assertEquals(3, $mock->get('start'));
        $this->assertEquals(4, $mock->get('length'));
        $this->assertEquals(5, $mock->get('draw'));
    }

    public function testDataTable()
    {
        $repo = new TestRepository();

        $result = $repo->dataTable(
            $this->getRequestMock(0, 10, 1)
        );

        $this->assertEquals(1, $result->draw);
        $this->assertEquals(10, $result->recordsTotal);
        $this->assertEquals(10, $result->recordsFiltered);
        $this->assertTrue(is_array($result->data));
        $this->assertEquals(10, count($result->data));
    }


    public function testDataTableOtherParams()
    {
        $repo = new TestRepository();
        $result = $repo->dataTable($this->getRequestMock(5, 5 ,3));

        $this->assertEquals(3, $result->draw);
        $this->assertEquals(10, $result->recordsTotal);
        $this->assertEquals(10, $result->recordsFiltered);
        $this->assertTrue(is_array($result->data));
        $this->assertEquals(5, count($result->data));
    }

    public function testDataTableAndScopeFilter()
    {
        $repo = new TestRepository();

        $result = $repo
            ->scope(function ($query) {
                $query->where('seq', '>', 5);
            })
            ->dataTable($this->getRequestMock(2, 5 ,1));

        $this->assertEquals(1, $result->draw);
        $this->assertEquals(5, $result->recordsTotal);
        $this->assertEquals(5, $result->recordsFiltered);
        $this->assertTrue(is_array($result->data));
        $this->assertEquals(3, count($result->data));
    }

    public function testDataTableAndCriteria()
    {
        $repo = new TestRepository();
        $result = $repo->criteria(OldThen2000Criteria::class)
            ->dataTable($this->getRequestMock(0, 5, 1));

        $this->assertEquals(1, $result->draw);
        $this->assertEquals(5, $result->recordsTotal);
        $this->assertEquals(5, $result->recordsFiltered);
        $this->assertEquals(5, count($result->data));
    }

    public function testDataTableLimitedFields()
    {
        $repo = new TestRepository();

        $result = $repo
            ->scope(function ($query) {
                $query->select(['id', 'seq']);
            })
            ->dataTable($this->getRequestMock(0, 5 ,1));

        $this->assertEquals(1, $result->draw);
        $this->assertEquals(10, $result->recordsTotal);
        $this->assertEquals(10, $result->recordsFiltered);
        $this->assertTrue(is_array($result->data));
        $this->assertEquals(5, count($result->data));
        $this->assertEquals(2, count($result->data[0]));
    }
}