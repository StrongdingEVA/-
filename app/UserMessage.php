<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    //
    protected $fillable = ['user_id', 'type','message_disc','etc','status','comval','comtype'];
}
