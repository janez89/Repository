<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:11
 */
use Illuminate\Database\Capsule\Manager as DB;

class RepositoryTest extends FunctionalTestCase
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

        for($i=0; $i < $this->count; $i++) {
            DB::table('tests')->insert([
                'title' => $faker->title,
                'seq' => $i+1,
                'created_at' => $faker->dateTime(),
                'updated_at' => $faker->dateTime(),
            ]);
        }
    }

    public function testFind()
    {
        $testRepository = new TestRepository();
        $test = $testRepository->find(1);

        $this->assertTrue($test->id == 1);
    }

    public function testCountingElements()
    {
        $repository = new TestRepository();

        $this->assertTrue($repository->count() == $this->count);
    }

    public function testFindBy()
    {
        $repository = new TestRepository();
        $tests = $repository->findBy('seq', 2);

        $this->assertTrue(count($tests) == 1);
        $this->assertTrue($tests[0]->seq == 2);
    }

    public function testAll()
    {
        $repository = new TestRepository();
        $all = $repository->all();

        $this->assertTrue(count($all) == $this->count);
    }

    public function testDelete()
    {
        $repository = new TestRepository();
        $repository->delete($this->count);
        $this->count--;

        $this->assertTrue($repository->count() == $this->count);

        $repository->delete([8,9]);
        $this->count -=2;

        $this->assertTrue($repository->count() == $this->count);
    }

    public function testPaginate()
    {
        $repository = new TestRepository();
        $items = $repository->paginate(1, 5);

        $this->assertTrue(count($items) == 5);
        $this->assertTrue($items[0]->id == 1);
    }

    public function testUserDefinedQuery()
    {
        $repository = new TestRepository();
        $this->assertTrue(count($repository->userDefinedQuery(1)) == 1);
    }


    public function testCreateData()
    {
        $repository = new TestRepository();
        $test = $repository->create(['title' => 'test 1', 'seq' => -1 ]);
        $this->count++;

        $this->assertArrayHasKey('title', $test);
        $this->assertTrue($repository->count() == $this->count);
    }

    public function testUpdateData()
    {
        $repository = new TestRepository();
        $res = $repository->update(['title' => 'test 1'], 1);

        $test = $repository->find(1);

        $this->assertArrayHasKey('title', $test);
        $this->assertTrue($test->title == 'test 1');

        // check override
        $test = $repository->find(2);
        $this->assertTrue($test->title != 'test 1');
    }

    public function testSaveMethodWithNewData()
    {
        $repository = new TestRepository();
        $model = $repository->getNew();
        $model->title = 'Test 2';
        $model->seq = -1;

        $repository->save($model);
        $this->count++;

        $this->assertTrue($model->id == $this->count);
    }

    public function testSaveMethodWithExistingData()
    {
        $repository = new TestRepository();
        $model = $repository->find(1);
        $model->title = 'Test 2';
        $model->seq = -1;

        $repository->save($model);
        $model = $repository->find(1);
        $this->assertTrue($model->title == 'Test 2');
    }

    public function testSaveNewDataArray()
    {
        $repository = new TestRepository();
        $repository->save(['title' => 'test new', 'seq' => 11]);

        $models = $repository->findBy('seq', 11);
        $this->assertTrue($models[0]->seq == 11);
        $this->assertTrue($models[0]->title == 'test new');
    }

    public function testSaveExistingDataArray()
    {
        $repository = new TestRepository();
        $repository->save(['title' => 'test existing', 'seq' => 5, 'id' => 5]);

        $model = $repository->find(5);
        $this->assertTrue($model->seq == 5);
        $this->assertTrue($model->title == 'test existing');
    }

    public function testSavingDataWithGuardedAttribute()
    {
        $repository = new TestRepository();
        $repository->save(['title' => 'test existing', 'seq' => 5, 'flag' => 1 ]);

        $this->assertNull($repository->find(11)->flag);

        // test mass assign
        $model = $repository->getNew(['title' => 'test', 'seq' => 5]);
        $model->flag = true;
        $repository->save($model);

        $this->assertTrue($repository->find($model->id)->flag);
    }

    public function testTransaction()
    {
        $repository = new TestRepository();
        $repository->transaction(function () use ($repository) {
            $repository->save(['title' => 'test existing', 'seq' => 11]);
        });

        $this->count++;

        $this->assertTrue($repository->count() == $this->count);
    }

    /**
     * @expectedException Exception
     */
    public function testTransactionFail()
    {
        $repository = new TestRepository();

        $repository->transaction(function () use ($repository) {
            $repository->save(['title' => 'test existing', 'seq' => 11]);
            throw new \Exception('Sample Exception');
        });

        $this->assertTrue($repository->count() == $this->count);
    }

    public function testPartialTransaction()
    {
        $repository = new TestRepository();

        $repository->beginTransaction();
        $repository->save(['title' => 'test existing', 'seq' => 11]);
        $repository->commit();

        $this->count++;

        $this->assertTrue($repository->count() == $this->count);
    }

    public function testPartialTransactionFail()
    {
        $repository = new TestRepository();

        $repository->beginTransaction();
        $repository->save(['title' => 'test existing', 'seq' => 11]);
        $repository->rollback();

        $this->assertTrue($repository->count() == $this->count);
    }

}
