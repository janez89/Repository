<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 29.
 * Time: 20:53
 */

use Illuminate\Database\Capsule\Manager as DB;

class InterceptorTest extends FunctionalTestCase
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

    public function testBaseScope()
    {
        $repository = new TestRepository();
        $first = $repository->scope(function ($q) {
            $q->where('seq', '>', 1);
        })->first();

        $this->assertTrue($first->seq == 2);
    }

    public function testQueryScope()
    {
        $repository = new TestRepository();
        $count = $repository->scope(function ($query) {
            $query->where('seq', '>', 0);
            $query->where(function ($q) {
                $q->where('seq', '>', 5);
            });
        })->count();

        $this->assertTrue($count == 5);
    }

    public function testQueryLimit()
    {
        $repository = new TestRepository();
        $all = $repository->scope(function ($q) {
            $q->take(5);
        })->all();

        $this->assertTrue(count($all) == 5);
    }
}