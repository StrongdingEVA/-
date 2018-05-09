<?php

namespace App;

use App\Http\Controllers\ArticleController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Answer extends Model
{
    public static $pageSize = 5;
    //
    protected $fillable = ['article_id', 'from_user_id','to_user_id', 'article_comment','comment_id','created_at','updated_at'];

    public function get_from_user_info(){
        return $this->belongsTo('App\User',"from_user_id","id");
    }

    public function get_to_user_info(){
        return $this->belongsTo('App\User',"to_user_id","id");
    }

    /**
     * @param $commentId
     * @return mixed
     *  根据评论获取回复
     */
    public static function getAnswerByComment($commentId,$aid = '',$page = 1,$offset = 0){
        $res = json_decode(Redis::get(ANS_KEY . $commentId),1);
        if(!$res){
            $res = self::select('id')->where(['comment_id' => $commentId])->get()->toArray();
            Redis::set(ANS_KEY . $commentId,json_encode($res));
        }

        if(!$res){
            return array('data' => array(),'totalPage' => 0,'sub' => 0,'nowPage' => 1);
        }
        $pageSize = self::$pageSize;
        $offset = $offset ? $offset : self::$pageSize;
        $c = count($res);
        $totalPage = ceil($c / $pageSize); //总的页数

        if($aid){
            $page_ = 0;
            foreach($res as $k => $v){
                if($v["id"] == $aid){
                    $page_ = $k + 1;
                    break;
                }
            }
            $page = ceil($page_ / $pageSize);
            $item = array_slice($res, 0, $pageSize * $page);
        }else{
            $item = array_slice($res, ($page - 1) * $pageSize, $offset);
        }
        //判断剩余记录数
        $sub = $c - count($item);

        $temp = array();
        foreach($item as $val){
            $temp[] = $val['id'];
        }
        $temp = $temp ? $temp : array(1);
        $result = self::where("comment_id",$commentId)
            ->whereIn('id',$temp)
            ->with("get_from_user_info")
            ->with("get_to_user_info")
            ->get()
            ->toArray();
        return array('data' => $result,'totalPage' => $totalPage,'sub' => $sub,'nowPage' => $page);
    }
}
