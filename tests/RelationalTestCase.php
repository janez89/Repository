<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 6:15
 */

use Illuminate\Database\Capsule\Manager as DB;

abstract class RelationalTestCase extends FunctionalTestCase
{
    protected $postByAuthor = 3;
    protected $authors = [];

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
        $this->seedData();
    }

    public function migrate()
    {
        DB::schema()->create('authors', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::schema()->create('posts', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('body');
            $table->unsignedInteger('author_id');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors');
        });
    }

    protected function seedData()
    {
        $faker = $this->getFaker();

        // authors
        $this->authors = [];
        for($i=0; $i < 3; $i++) {
            $this->authors[] = DB::table('authors')->insertGetId([
                'name' => $faker->name(),
                'created_at' => $faker->dateTime(),
                'updated_at' => $faker->dateTime(),
            ]);
        }

        foreach ($this->authors AS $authorId) {
            for($i=0; $i < $this->postByAuthor; $i++) {
                DB::table('posts')->insert([
                    'title' => $faker->title,
                    'body' => $faker->paragraph(),
                    'author_id' => $authorId,
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ]);
            }
        }
    }
}