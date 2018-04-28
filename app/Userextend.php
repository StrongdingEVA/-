<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        $userEx = $userId ? Userextend::where("user_id",$userId)->first() : Userextend::where("user_id",Auth::user()->id)->first();
        return $userEx->article_collection ? json_decode($userEx->article_collection) : array();
    }

    public static function getUserExtendById($userId = ''){
        if(!$userId){
            return array();
        }
        return self::where('user_id',$userId)
            ->select('*')
            ->get();
    }

}
