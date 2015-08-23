<?php namespace Test\services;

use Test\TestCase;
use Vasles\Models\KeyValue;
use Vasles\Services\KeyValueStore;


class KeyValueStoreTest extends TestCase {

    /**
     * @var KeyValueStore
     */
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new KeyValueStore(new KeyValue() );
    }
    /**
     * @test
     */
    public function searchByRange()
    {
        $key = "issues:cat:parsonline:";
        $actual= $this->model->searchByRange($key.'2015-07-01', $key.'2015-09-01');
        $expected = ['issues:cat:parsonline:2015-08-17'=>1,'issues:cat:parsonline:2015-08-18'=>10,'issues:cat:parsonline:2015-08-19'=>5];

        $this->assertEquals($actual,$expected);
    }


    public function testGet()
    {
        $actual = $this->model->get("issues:cat:parsonline:2015-08-18");
        $this->assertEquals($actual, 10);
    }

    /**
     * @test
     */
    public function has()
    {
        $this->assertTrue($this->model->has("issues:cat:parsonline:2015-08-17"));
    }

    /**
     * @test
     */
    public function searchByPrefix()
    {
        $prefix="issues:cat:parsonline";
        $actual=$this->model->searchByPrefix($prefix);
        $expected=["issues:cat:parsonline:2015-08-17" => 1,"issues:cat:parsonline:2015-08-18" => 10, "issues:cat:parsonline:2015-08-19" => 5];
        $this->assertEquals($expected,$actual);
    }

    /**
     * @test
     */
    public function create()
    {
        $this->model->create("issues:cat:saeed:2015-08-19");
        $result = $this->model->has("issues:cat:saeed:2015-08-19");
        $this->assertTrue($result);
        $this->assertEquals($this->model->get("issues:cat:saeed:2015-08-19"),1);
    }

    /**
     * @test
     */
    public function update()
    {
        $this->model->update("issues:cat:afranet:2015-08-17",5);
        $this->assertEquals($this->model->get("issues:cat:afranet:2015-08-17"),8);
    }

    public function testSearchByRange()
    {
        $actual = $this->model->searchByRange("issues:cat:afranet:2015-08-16","issues:cat:afranet:2015-08-19");
        $expected=["issues:cat:afranet:2015-08-17" => 3,"issues:cat:afranet:2015-08-18" => 7];
        $this->assertEquals($expected,$actual);
    }

    /**
     * @test
     */
    public function createOrUpdate()
    {
        $this->model->createOrUpdate("issues:cat:afranet:2015-08-17");
        $result = $this->model->get("issues:cat:afranet:2015-08-17");
        $this->assertEquals($result, 4);
        $this->model->createOrUpdate("issues:cat:saeed:2015-08-19");
        $result = $this->model->has("issues:cat:saeed:2015-08-19");
        $this->assertTrue($result);
        $this->assertEquals($this->model->get("issues:cat:saeed:2015-08-19"),1);
    }

    /**
     * @test
     */
    public function increaseBy()
    {
        $this->model->increaseBy("issues:cat:afranet:2015-08-17", 2);
        $this->assertEquals($this->model->get("issues:cat:afranet:2015-08-17"), 5);
    }
}
