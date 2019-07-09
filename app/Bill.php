<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    const CREATED_AT = "entry_date";
    const UPDATED_AT = null;

    protected $primaryKey = 'id';

    public function scopeLike($query, $field, $value)
    {
        return $query->where($field, 'LIKE', '%{$value}%');
    }
}
