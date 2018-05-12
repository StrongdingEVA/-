<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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
        Redis::set(ART_KEY . $articleId,null);
        $info = json_decode(Redis::get(ART_KEY . $articleId),1);
        if(!$info){
            $info = self::where("id",$articleId)->with("getUsername")->first()->toArray();
            Redis::set(ART_KEY . $articleId,json_encode($info));
        }
        $info['article_source_pic'] = explode(',',$info['article_source_pic']);
        return $info;
    }

    /**
     * 获取用户发布的文章信息
     * @param string $userId
     */
    public static function getArticleToUser($userId = ""){
        $userId = $userId ? $userId : Auth::user()->id;
        $info = json_decode(Redis::get(ART_KEY_UROD . $userId),1);
        if(!$info){
            $info = self::where(["user_id"=>$userId,"is_show"=>1])->orderBy("id","desc")->paginate(5)->toArray();
            Redis::set(ART_KEY_UROD . $userId,json_encode($info));
        }
        return $info;
    }

    /**
     * 推荐一些热门评价的文章
     * @param string $userId
     * @return mixed
     */
    public static function getArticleForHot($pageSize = 4){
        //最近发布 并且评价较多的文章
        $t = time() - 3600 * 24 * 5;
        $info = json_decode(Redis::get(ART_KEY_HOT),1);
        if(!$info){
            $info = self::where(["article_status"=>0,"is_show"=>1])
                ->where("created_at",">=",$t)
                ->orderBy("comments","desc")
                ->paginate($pageSize)
                ->toArray()['data'];
            Redis::set(ART_KEY_HOT,json_encode($info));
        }
        return $info;
    }

    /**
     * 推荐一些用户发布过的文章
     * @param string $userId
     */
    public static function getHistArticle($userId){
        $userId = $userId ? $userId : Auth::user()->id;
        $info = json_decode(Redis::get(ART_KEY_UROD . $userId),1);
        if(!$info){
            $info = self::where(["user_id"=>$userId,"is_show"=>1])->orderBy("id","desc")->paginate(5)->toArray()['data'];
            Redis::set(ART_KEY_UROD . $userId,json_encode($info));
        }
        return $info;
    }

    /**
     * 首页滚动加载 分页
     * @param array $fields
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function getList($fields = array(),$where = array(),$whereIn = array(),$order = array(),$page = 1,$pageSize = 10){
        $res = self::select('id')->where($where);
        if(!empty($whereIn)){
            $res = $res->whereIn($whereIn[0],$whereIn[1]);
        }
        $res = $res->get()->toArray();
        if(!$res){
            return array(array('data' => array()),0);
        }
        $item = array_slice($res, ($page - 1) * $pageSize, $pageSize); //注释1
        $temp = array();
        foreach($item as $val){
            $temp[] = $val['id'];
        }
        $result = self::select($fields)
            ->where($where)
            ->whereIn('id',$temp)
            ->orderBy($order[0],$order[1])
            ->with("getUsername")
            ->paginate($pageSize)
            ->toArray();

        $total = count($res);
        $currentPage = "";
        $paginatorAns = new LengthAwarePaginator($result, $total, $pageSize, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);
        $paginatorAns->setCurrPage($page);
        $result = $paginatorAns->toArray()['data'];
        return array($result,$total);
    }

    /**
     * 获取首页滚动文章
     * 首先获取推荐文章 如果没有推荐文章则选择今日浏览次数最多的文章
     */
    public static function getSrollArticle(){
        $result = json_decode(Redis::get(ART_KEY_SCL),1);
        if(!$result){
            $result = self::select('article_title','id')->where("is_recommend",1)->orderBy("views","desc")->paginate(5)->toArray()['data'];

            if(!count($result)){
                $result = Article::select('article_title','id')->where(['is_show' => 1])->orderBy("views","desc")->paginate(5)->toArray()['data'];
            }
            Redis::set(ART_KEY_SCL,json_encode($result));
        }
        return $result;
    }

    /**
     * 更新文章评论次数
     * @param $articleId  文章ID
     * @param int $add  ture 加 false 减
     */
    public static function updateArticleComment($articleId,$add = 1){
        return $add ? self::where("id","{$articleId}")->increment("comments",1) : self::where("id","{$articleId}")->decrement("comments",1);
    }

    public static function updateViews($articleId){
        $res = self::where("id",$articleId)->increment("views",1);
        if($res){
            Redis::set(ART_KEY . $articleId,null);
        }
        return $res;
    }
}
