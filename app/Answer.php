<?php

namespace App;

use App\Http\Controllers\ArticleController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

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
        $res = self::select('id')->where(['comment_id' => $commentId])->get()->toArray();
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

    /**
     * @param $commentId
     * 获取评论的回复s
     */
    public static function getAnswer_2($commentId,&$total,&$totalPage,&$currentPage,$current_page = 1,$isAjax = 0){
        if(!$commentId){
            return array();
        }
        $perPage = self::$pageSize; //每页记录数
        //判断当前回复在第几页
        $k = 0;
        $answer = Answer::where("comment_id",$commentId)->orderBy("id","asc")->with("get_from_user_info")->with("get_to_user_info")->get()->toarray();

        if(count($answer)==0){
            return false;
        }

        if(!$isAjax) {
            foreach ($answer as $key => $val) {
                if ($val["id"] == $commentId) {
                    $k = $key + 1;
                }
            }
            $current_page = ceil($k / $perPage);
        }

        $item = array_slice($answer, ($current_page-1) * $perPage, $perPage); //注释1
        $total = count($answer);
        $totalPage = ceil($total / $perPage);
        $currentPage = "";
        $paginatorAns = new LengthAwarePaginator($item, $total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);
        $paginatorAns->setCurrPage($current_page);
        $answer = $paginatorAns->toArray()['data'];

        $timeStar = 0;
        foreach($answer as $k => $v){
            $answer[$k]["article_comment"] = htmlspecialchars_decode($v["article_comment"]);
            $timeTemp = strtotime($v["created_at"]);
            if(($timeTemp - $timeStar) >= 600){
                $timeStar = $timeTemp - $timeStar;
            }else{
                $answer[$k]["created_at"] = null;
            }
        }

        $total = $total;
        $totalPage = $totalPage;
        $currentPage = $current_page;
        return $answer;
    }
}
