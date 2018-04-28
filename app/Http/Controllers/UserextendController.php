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

    /**
     * 判断当前用户是否关注文章作者
     * @param $userId 用户Id
     */
    public static function isFoucs($userIdTo){
        $userIdNow = @Auth::user()->id;
        if(!$userIdNow){
            return 0;
        }
        $userExNow = Userextend::where("user_id",$userIdNow)->first();
        $userFoucsNow = $userExNow->user_foucs ? json_decode($userExNow->user_foucs) : array();
        if(in_array($userIdTo,$userFoucsNow)){
            return 1;
        }
        return 0;
    }

    /**
     * 判断当前用户与此用户相互关注
     * @param $userId 用户Id
     */
    public static function isFoucsBouth($userIdTo){
        $userIdNow = @Auth::user()->id;
        if(!$userIdNow){
            return 0;
        }
        $userExNow = Userextend::where("user_id",$userIdNow)->first();
        $userExTo = Userextend::where("user_id",$userIdTo)->first();
        $userFoucsNow = empty($userExNow->user_foucs) ? array() : json_decode($userExNow->user_foucs);
        $userFoucsTo = empty($userExTo->user_foucs) ? array() : json_decode($userExTo->user_foucs);
        if(in_array($userIdTo,$userFoucsNow) && in_array($userIdNow,$userFoucsTo)){
            return 1;
        }
        return 0;
    }
}
