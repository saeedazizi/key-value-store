<?php

namespace Vasles\Services;

use Vasles\Models\KeyValue;

class KeyValueStore
{
    /**
     * @var KeyValue
     */
    private $keyValue;

    public function __construct(KeyValue $keyValue)
    {
        $this->keyValue = $keyValue;
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function get($key)
    {
        /** @var KeyValue $model */
        $model = $this->keyValue->newQuery()->where('key', '=', $key)->first();
        return $model === null ? null : $model->value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) != null;
    }

    /**
     * @param string $keyPrefix
     * @return array
     */
    public function searchByPrefix($keyPrefix)
    {
        str_replace("%", "\\%", $keyPrefix);
        /** @var KeyValue[] $models */
        $models = $this->keyValue->newQuery()->where('key', 'like', $keyPrefix . '%')->get();
        $keyValues = [];
        foreach ($models as $model) {
            $keyValues[$model->key] = $model->value;
        }
        return $keyValues;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function create($key)
    {
        $this->keyValue->create([
            'key'   => $key,
            'value' => 1,
        ]);
    }

    public function update($key, $value)
    {
        $model = $this->keyValue->newQuery()->where('key', '=', $key)->firstOrFail();
        $model->value += $value;
        $model->save();
    }

    public function searchByRange($from, $to)
    {
        $models = $this->keyValue->newQuery()->where('key','>=', $from)->where('key','<=', $to)->get();
        $keyValues = [];
        foreach ($models as $model) {
            $keyValues[$model->key] = $model->value;
        }
        return $keyValues;
    }

    public function createOrUpdate($key)
    {
        if($this->has($key))
        {
            $this->update($key, 1);
        }
        else
        {
            $this->create($key);
        }
    }

    public function increaseBy($key, $n = 1)
    {
        $this->update($key, $n);
    }

}