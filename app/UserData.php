<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    //
    protected $table = 'user_data';
    protected $primaryKey = 'id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
