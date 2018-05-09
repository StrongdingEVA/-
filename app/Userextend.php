<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class Userextend extends Model
{
    //
    protected $fillable = ['user_id', 'article_collection','article_views','user_foucs','user_fans'];

    /**
     * @param string $userId
     * @return array
     *  获取最近收藏的文章进行推荐
     */
    public static function getFrequencyCate($userId = ""){
        $userId = $userId ? $userId : Auth::user()->id;
        $userCollection = self::getUserFrequency($userId);
        $c = count($userCollection);
        if($c == 0){
            return array();
        }
        $min = $c > 2 ? $c -2 : 0;
        $arrTemp = array();
        for($i = $c -1 ;$i >= $min;$i--){//只取最新的两个收藏
            $articleInfo = Article::where("id",$userCollection[$i])->first();
            $arrTemp[] = $articleInfo->category;
        }
        return $arrTemp;
    }

    /**
     * 获取用户最近收藏的文章
     */
    public static function getUserFrequency($userId = ""){
        $userEx = $userId ? self::getUserExtendById($userId) : self::getUserExtendById(Auth::user()->id);
        return $userEx['article_collection'] ? json_decode($userEx['article_collection']) : array();
    }

    /**
     * 获取用户所有拓展信息
     * @param string $userId
     * @return array
     */
    public static function getUserExtendById($userId = ''){
        if(!$userId){
            return array();
        }
        $info = json_decode(Redis::get(USER_EXT . $userId),1);
        if(!$info){
            $info = self::where('user_id',$userId)
                ->select('*')
                ->first()
                ->toArray();
            Redis::set(USER_EXT . $userId,json_encode($info));
        }
        return $info;
    }

    public static function updateById($userId,$param){
        $res = self::where("user_id",$userId)->update($param);
        if($res){
            Redis::set(USER_EXT . $userId,null);
        }
        return $res;
    }

    /**
     * 获取用户的粉丝
     * @param string $userId
     * @return array|bool|mixed
     */
    public static function useFans($userId = ""){
        $userEx = self::getUserExtendById($userId);
        return empty($userEx['user_fans']) ? array() : json_decode($userEx['user_fans']);
    }

    /**
     * 获取用户的关注
     * @param string $userId
     * @return array|bool|mixed
     */
    public static function useFoucs($userId = ""){
        $userEx = self::getUserExtendById($userId);
        return empty($userEx['user_foucs']) ? array() : json_decode($userEx['user_foucs']);
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
        $userFoucsNow = self::useFoucs($userIdNow);
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
        $userFoucsTo = self::useFoucs($userIdTo);
        if(self::isFoucs($userIdTo) && in_array($userIdNow,$userFoucsTo)){
            return 1;
        }
        return 0;
    }
}
