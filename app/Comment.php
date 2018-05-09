<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class Comment extends Model
{
    //
    protected $fillable = ['article_id', 'user_id', 'article_comment','answer_count','good_count','bad_count','created_at','updated_at'];

    public static function getCommentList($key,$articleId,$where = array(),$order = array(),$page = 1,$pageSize = 10){
        $res = json_decode(Redis::get(ART_KEY_COM . $articleId),1);
        if(!$res){
            $res = self::select('id')->where($where);
            $res = $res->get()->toArray();
            Redis::set(ART_KEY_COM . $articleId,json_encode($res));
        }
        if(!$res){
            return array('paginator' => array(),'data' => array());
        }

        if($key){
            $page_ = 0;
            foreach($res as $k => $v){
                if($v["id"] == $key){
                    $page_ = $k + 1;
                    break;
                }
            }
            $page = ceil($page_ / $pageSize);
        }

        $item = array_slice($res, ($page - 1) * $pageSize, $pageSize); //注释1
        $temp = array();
        foreach($item as $val){
            $temp[] = $val['id'];
        }
        $temp = $temp ? $temp : array(1);
        $result = json_decode(Redis::get(ART_KEY_COM_PAGE . implode(',',$temp)),1);
        if(!$result){
            $result = self::join("users","users.id","=","comments.user_id")
                ->select("comments.*","users.username","users.logo")
                ->where($where)
                ->whereIn('comments.id',$temp)
                ->orderBy($order[0],$order[1])
                ->get()
                ->toArray();
            Redis::set(ART_KEY_COM_PAGE . implode(',',$temp),json_encode($result));
        }

        $total = count($res);
        $currentPage = "";
        $paginatorAns = new LengthAwarePaginator($result, $total, $pageSize, $currentPage, [
            'path' => '/article_detail/' . $articleId,  //注释2
            'pageName' => 'page',
        ]);
        $paginatorAns->setCurrPage($page);
        $result = $paginatorAns->toArray()['data'];
        return array('paginator' => $paginatorAns,'data' => $result);
    }

    /**
     * 判读文章是否被该用户评论
     * @param $articleId 文章ID
     * @param string $userId 用户ID
     * @return int
     */
    public static function judgeComment($articleId,$userId = ""){
        $userId = $userId ? $userId : Auth::user()->id;
        $commentInfo = self::where(["article_id"=>$articleId,"user_id"=>$userId])->get();
        return count($commentInfo);
    }
}
