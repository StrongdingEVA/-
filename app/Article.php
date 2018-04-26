<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = ['category', 'article_title', 'article_disc','article_content','article_thumb','user_id','article_status','is_show','views','collections','comments','is_recommend','collector'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 关联用户
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getUsername(){
        return $this->belongsTo('App\User','user_id',"id");
    }

    public function getCateName(){
        return $this->belongsTo('App\Category',"category","id");
    }

    /**
     * @param $articleId
     * @return bool
     * 获取文章内容
     */
    public static function getArticleInfo($articleId){
        if(!$articleId){
            return false;
        }
        return Article::where("id",$articleId)->with("getUsername")->first();
    }

    /**
     * 获取用户发布的文章信息
     * @param string $userId
     */
    public static function getArticleToUser($userId = ""){
        return $userId ? Article::where(["user_id"=>$userId,"is_show"=>1])->orderBy("id","desc")->paginate(5) : Article::where(["user_id"=>Auth::user()->id,"is_show"=>1])->orderBy("id","desc")->paginate(5);
    }
}
