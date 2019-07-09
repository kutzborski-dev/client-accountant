<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table = 'user_settings';
    protected $primaryKey = 'id';

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
