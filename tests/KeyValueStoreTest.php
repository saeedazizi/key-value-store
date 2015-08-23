<?php

use Illuminate\Database\Eloquent\Builder;
use Mockery\MockInterface;
use Opilo\KeyValue\Eloquent\KeyValue;
use Opilo\KeyValue\KeyValueStore;

class KeyValueStoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var KeyValue|MockInterface
     */
    private $model;

    /**
     * @var KeyValueStore
     */
    protected $store;

    public function setUp()
    {
        parent::setUp();
        $this->model = Mockery::mock(KeyValue::class);
        $this->store = new KeyValueStore($this->model);
    }

    public function test_get_when_it_finds_the_key()
    {
        $builder = Mockery::mock(Builder::class);
        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
        $key = 'key-name:1';
        $builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $builder->shouldReceive('first')->once()->with()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertEquals($value, $this->store->get($key));
    }
}