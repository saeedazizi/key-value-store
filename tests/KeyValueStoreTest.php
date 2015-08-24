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
        $builder->shouldReceive('first')->once()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertEquals($value, $this->store->get($key));
    }

    public function test_has_a_key_in_table()
    {
        $builder = Mockery::mock(Builder::class);
        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
        $key = 'key-name:1';
        $builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $builder->shouldReceive('first')->once()->with()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertTrue($this->store->has($key));
    }

    public function test_search_keys_by_prefix()
    {
        $builder = Mockery::mock(Builder::class);
        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
        $key = 'key-name:1';
        $builder->shouldReceive('where')->once()->with('key', 'like', $key.'%')->andReturnSelf();
        $builder->shouldReceive('get')->once()->andReturn(array($this->model));
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->model->shouldReceive('getAttribute')->once()->with('key')->andReturn($key);
        $expected[$key]=$value;
        $this->assertEquals($expected, $this->store->searchByPrefix($key));
    }

    public function test_create_a_key_value_row_in_table()
    {
        $builder = Mockery::mock(Builder::class);
        $key = 'key-name:1';
        $this->model->shouldReceive('create')->once()->with(['key' =>$key,'value' =>1])->andReturn($builder);
        $this->store->create($key);
        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
        $key = 'key-name:1';
        $builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $builder->shouldReceive('first')->once()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertTrue($this->store->has($key));
    }

//    public function test_update_value_of_exsiting_key()
//    {
//        $builder = Mockery::mock(Builder::class);
//        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
//        $key = 'key-name:1';
//        $builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
//        $builder->shouldReceive('firstOrFail')->once()->andReturn($this->model);
//        $value = 2;
//        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
//        $this->model->shouldReceive('setAttribute')->once()->with($key,$value)->andReturnSelf();
//        $this->store->update($key, $value);
//        $this->model->shouldReceive('newQuery')->once()->andReturn($builder);
//        $builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
//        $builder->shouldReceive('first')->once()->andReturn($this->model);
//        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
//        $this->assertEquals($value, $this->store->get($key));
//    }
}