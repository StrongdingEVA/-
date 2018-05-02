<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Comment extends Model
{
    //
    protected $fillable = ['article_id', 'user_id', 'article_comment','answer_count','good_count','bad_count','created_at','updated_at'];

    public static function getCommentList($key,$where = array(),$order = array(),$page = 1,$pageSize = 10){
        $res = self::select('id')->where($where);
        $res = $res->get()->toArray();
        if(!$res){
            return array();
        }

        if($key){
            foreach($res as $k => $v){
                if($v["id"] == $key){
                    $k = $key + 1;
                }
            }
            $current_page = ceil($k / $pageSize);
        }

        $item = array_slice($res, ($current_page - 1) * $pageSize, $pageSize); //注释1
        $temp = array(1);
        foreach($item as $val){
            $temp[] = $val['id'];
        }
        $result = self::join("users","users.id","=","comments.user_id")
            ->select("comments.*","users.username","users.logo")
            ->where($where)
            ->whereIn('comments.id',$temp)
            ->orderBy($order[0],$order[1])
            ->get()
            ->toArray();

        $total = count($res);
        $currentPage = "";
        $paginatorAns = new LengthAwarePaginator($result, $total, $pageSize, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);
        $paginatorAns->setCurrPage($page);
        $data = $paginatorAns->toArray()['data'];
        return array('paginator' => $paginatorAns,'data' => $data);
    }
}
