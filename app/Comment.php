<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = ['article_id', 'user_id', 'article_comment','answer_count','good_count','bad_count','created_at','updated_at'];
}
