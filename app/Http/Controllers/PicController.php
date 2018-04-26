<?php

namespace App\Http\Controllers;

use App\Category;
use App\Pic;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PicController extends Controller
{
    public function getPics(){
        //首页
        $userInfo = Auth::user();
        //查询分类
        $cateInfo = Category::where("level",0)->orderBy("id","asc")->get();
        $actionLi = 0;
        $pic = Pic::orderBy("id","asc")->paginate(20);
        return view("Home.pic",compact("pic","userInfo","cateInfo","actionLi"));
    }
}
