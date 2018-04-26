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

    public function getAnswerAjax(Request $request,$id,$page){
        if ($page) {
            $current_page = $page;
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }
        $total = $totalPage = $currentPage = 0;
        $answer = Answer::getAnswer_2($id,$total,$totalPage,$currentPage,$current_page,1);
        \Helpers::echoJsonAjax(0,"获取回复成功",array("total"=>$total,"totalPage"=>$totalPage,"currentPage"=>$currentPage,"answer"=>$answer),1);
    }
}
