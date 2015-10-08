<?php

use Illuminate\Database\Capsule\Manager as DB;

abstract class FunctionalTestCase extends PHPUnit_Framework_TestCase
{
    protected $_faker;

    public function setUp()
    {
        date_default_timezone_set('Europe/Budapest');
        $this->configureDatabase();
    }

    protected function configureDatabase()
    {
        $db = new DB;
        $db->addConnection(array(
            'driver'    => 'sqlite',
            'database'  => ':memory:',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ));
        $db->bootEloquent();
        $db->setAsGlobal();
        DB::connection()->enableQueryLog();
    }

    /**
     * @return \Faker\Generator
     */
    protected function getFaker()
    {
        if ($this->_faker == NULL)
            $this->_faker = Faker\Factory::create();

        return $this->_faker;
    }

    public function tearDown()
    {
        parent::tearDown();
        //print_r(DB::getQueryLog());
    }
}
