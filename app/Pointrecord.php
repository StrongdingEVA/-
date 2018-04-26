<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pointrecord extends Model
{
    //
    protected $fillable = ['user_id', 'type', 'point','describe'];
}
