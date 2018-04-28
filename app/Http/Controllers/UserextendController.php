<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use App\Userextend;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserextendController extends Controller
{
    /**
     * 更新用户收藏的帖子
     * @type 1 增加收藏  0 取消收藏
     * @param $articleId
     * @return bool
     */
    public static function updateCollect($articleId,$type = 1){
        $userInfo = Auth::user();
        $userId = $userInfo->id;
        if(!Userextend::where("user_id",$userId)->first()){
            $arrTemp = array("user_id"=>$userId,"article_collection"=>"","	article_views"=>"");
            Userextend::create($arrTemp);
        }

        $extendInfo = Userextend::where("user_id",$userId)->first();
        $articleCollection = $extendInfo->article_collection ? json_decode($extendInfo->article_collection,1) : array();
        if($type == 1){
            if(in_array($articleId,$articleCollection)){
                return -1;
            }
//          $collectArr = $articleCollection ? json_decode($articleCollection) : array();
            $articleCollection[] = $articleId;
            return Userextend::where("user_id",$userId)->update(["article_collection" => json_encode($articleCollection)]);
        }else{
            if(!in_array($articleId,$articleCollection)){
                return -1;
            }
            unset($articleCollection[array_search($articleId,$articleCollection)]);
            return Userextend::where("user_id",$userId)->update(["article_collection" => json_encode($articleCollection)]);
        }

    }
}
