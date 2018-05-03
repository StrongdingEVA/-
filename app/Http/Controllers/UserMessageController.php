<?php

namespace App\Http\Controllers;

use App\UserMessage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\AssignThisVariablePass;

class UserMessageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @type 1有人回复你，2有人关注你，3有人@你
     * @comType 1 评论 2 回复
     * @return \Illuminate\Http\Response
     */
    public static function create($param){
        $userId = $param['from_id'] ? $param['from_id'] : Auth::user()->id;
        $arr = array(
            "from_id" => $userId,
            "to_id" => $param['to_id'],
            "type" => $param['type'],
            "message_disc" => $param['disc'],
            "article_id" => $param['article_id'],
            "comtype" => $param['com_type'],
            "ans_id" => $param['ans_id'],
            "comment_id" => $param['comment_id']
        );

        return UserMessage::create($arr);
    }

    public function getUserMessage(){
        $userId = @Auth::user()->id;
        if(!$userId){\Helpers::echoJsonAjax(-1,"未登录");}
        $info = UserMessage::getMsgByToUid($userId);
        \Helpers::echoJsonAjax(1,"获取信息成功",$info,1);
    }
}
