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
    public static function create($type,$disc,$comVal,$userId = "",$etc = 0,$comType = 1){
        $arr = array("user_id"=>$userId,"type"=>$type,"message_disc"=>$disc,"etc"=>$etc,"comtype"=>$comType,"comval"=>$comVal);
        //print_r($arr);die;
        //插入一个信息，提醒用户，有消息
        $userId = $userId ? $userId : Auth::user()->id;
        if(!$type || !$disc){return false;}
        return UserMessage::create($arr);
    }

    public function getUserMessage(){
        $userId = @Auth::user()->id;
        if(!$userId){\Helpers::echoJsonAjax(-1,"未登录");}
        $info = UserMessage::where(["user_id"=>$userId,"status"=>0])->orderBy("created_at","desc")->get();
        foreach($info as $k => $v){
            ArticleController::encrytById($info[$k],"etc");
        }
        \Helpers::echoJsonAjax(1,"获取信息成功",$info,1);
    }
}
