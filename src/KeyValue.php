<?php

namespace Vasles\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KeyValue
 * @package Vasles\Models
 *
 * @property string $key
 * @property string $value
 */
class KeyValue extends Model
{
    protected $table = 'key_value_store';

    protected $guarded = ['id'];

    public $timestamps = false;
}