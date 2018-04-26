<?php

namespace App\Http\Controllers;

use App\Pointrecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PointrecordController extends Controller
{
    /**
     * @param $type 1评论积分  2 发布文章积分 3 登录积分 4 点赞积分 5 取消点赞扣除积分 6 删除评论扣除积分
     * @param string $describe 描述
     * @param string $userId
     * @return bool|static
     */
    public static function insertRecord($type,$describe = "",$userId = ""){
        if(!$type){
            return false;
        }
        $point = 0;
        switch($type){
            case 1 :
                $point = COMMENT_POINT;
                $describe = $describe ? $describe : "评论增加积分";
                break;
            case 2 :
                $point = POST_ARTICLE_POINT;
                $describe = $describe ? $describe : "发布文章增加积分";
                break;
            case 3 :
                $point = LOGIN_POINT;
                $describe = $describe ? $describe : "登录增加积分";
                break;
            case 4 :
                $point = COLLECTION_POINT;
                $describe = $describe ? $describe : "点赞增加积分";
                break;
            case 5 :
                $point = COLLECTION_CANCEL_POINT;
                $describe = $describe ? $describe : "取消点赞扣除积分";
                break;
            case 6 :
                $point = COMMENT_CANCEL_POINT;
                $describe = $describe ? $describe : "删除评论扣除积分";
                break;
            default :
                $point = 0;
                break;
        }

        if(!$point){
            return false;
        }
        $userId = $userId ? $userId : Auth::user()->id;
        return Pointrecord::create(array("user_id"=>$userId,"type",$type,"point"=>$point,"describe"=>$describe));
    }
}
