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

    /**
     * @var Builder\MockInterface
     */
    protected $builder;

    protected function tearDown() {
        Mockery::close();
    }

    public function setUp()
    {
        parent::setUp();
        $this->model = Mockery::mock(KeyValue::class);
        $this->store = new KeyValueStore($this->model);
        $this->builder = Mockery::mock(Builder::class);
    }

    public function test_get_when_it_finds_the_key()
    {

        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('first')->once()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertEquals($value, $this->store->get($key));
    }

    public function test_has_a_key_in_table()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('first')->once()->with()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertTrue($this->store->has($key));
    }

    public function test_search_keys_by_prefix()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', 'like', $key.'%')->andReturnSelf();
        $this->builder->shouldReceive('get')->once()->andReturn(array($this->model));
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->model->shouldReceive('getAttribute')->once()->with('key')->andReturn($key);
        $expected[$key]=$value;
        $this->assertEquals($expected, $this->store->searchByPrefix($key));
    }

    public function test_create_a_key_value_row_in_table()
    {
        $key = 'key-name:1';
        $this->model->shouldReceive('create')->once()->with(['key' =>$key,'value' =>1])->andReturn($this->builder);
        $this->store->create($key);
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('first')->once()->andReturn($this->model);
        $value = 'the value of key-name:1';
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->assertTrue($this->store->has($key));
    }

    public function test_update_value_of_exsiting_key()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('firstOrFail')->once()->andReturn($this->model);
        $value = 2;
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->model->shouldReceive('setAttribute')->once()->with('value', 25);
        $this->model->shouldReceive('save')->once()->andReturn(true);
        $this->store->update($key, 23);
    }

    public function test_search_keys_by_range()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key1 = 'key-name:1';
        $key2 = 'key-name:2';
        $value1=1;
        $value2=2;
        $this->builder->shouldReceive('where')->once()->with('key', '>=', $key1)->andReturnSelf();
        $this->builder->shouldReceive('where')->once()->with('key', '<=', $key2)->andReturnSelf();
        $stubs = [
            new KeyValueStub($key1, $value1),
            new KeyValueStub($key2, $value2),
        ];
        $this->builder->shouldReceive('get')->once()->andReturn($stubs);
        $expected =[
            $key1 => $value1,
            $key2 => $value2,
        ];
        $this->assertEquals($expected, $this->store->searchByRange($key1,$key2));
    }

    public function create_new_key_value_or_update_new_key()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('first')->once()->with()->andReturn($this->model);
        $value = 2;
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);

        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('firstOrFail')->once()->andReturn($this->model);
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->model->shouldReceive('setAttribute')->once()->with('value', 4);
        $this->model->shouldReceive('save')->once()->andReturn(true);

        $this->model->shouldReceive('create')->once()->with(['key' =>$key,'value' =>1])->andReturn($this->builder);
        $this->store->create($key);
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);;
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('first')->once()->andReturn($this->model);
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);

        $this->store->createOrUpdate($key);
    }

    public function increase_value_of_key_by_N()
    {
        $this->model->shouldReceive('newQuery')->once()->andReturn($this->builder);
        $key = 'key-name:1';
        $this->builder->shouldReceive('where')->once()->with('key', '=', $key)->andReturnSelf();
        $this->builder->shouldReceive('firstOrFail')->once()->andReturn($this->model);
        $value = 2;
        $this->model->shouldReceive('getAttribute')->once()->with('value')->andReturn($value);
        $this->model->shouldReceive('setAttribute')->once()->with('value', 4);
        $this->model->shouldReceive('save')->once()->andReturn(true);
        $this->store->increase($key, $value);
    }
}

class KeyValueStub
{
    public $key;
    public $value;

    /**
     * KeyValueStub constructor.
     * @param $key
     * @param $value
     */
    public function __construct($key, $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }
}