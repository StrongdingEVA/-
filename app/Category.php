<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    //
    public function categoryName(){
        return $this->hasMany(Article::class);
    }
}
