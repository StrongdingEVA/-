<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * @param $articleId
     * 获取文章的评论
     */
    public static function getComment($articleId){
        return $articleId ? Comment::join("users","users.id","=","comments.user_id")
            ->select("comments.*","users.username","users.logo")->where("article_id",$articleId)->paginate(15) : array();
    }
}
