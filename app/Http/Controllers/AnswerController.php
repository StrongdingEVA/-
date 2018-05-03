<?php

namespace App\Http\Controllers;

use App\Answer;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Symfony\Component\Console\Helper\Helper;

class AnswerController extends Controller
{
    /**
     * @param $commentId
     * 获取评论的回复
     */
    public static function getAnswer($commentId){
        if(!$commentId){
            return array();
        }
        $answer = Answer::getAnswer($commentId);
        return $answer;
    }

    public function getAnswerAjax(Request $request,$id,$page,$oft = 5){
        $ansList = Answer::getAnswerByComment($id,0,$page,$oft);
        \Helpers::echoJsonAjax(0,"获取回复成功",array("answer"=>$ansList['data']),1);
    }
}
