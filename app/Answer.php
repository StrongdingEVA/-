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
    public static function getAnswer($commentId,&$totalPage){
        $answer = Answer::where("comment_id",$commentId)->with("get_from_user_info")->with("get_to_user_info")->paginate(self::$pageSize);

        $timeStar = 0;
        foreach($answer as $k => $v){
            $answer[$k]["article_comment"] = htmlspecialchars_decode($v["article_comment"]);
            $timeTemp = strtotime($v["craeted_at"]);
            if(($timeTemp - $timeStar) >= 600){
                $timeStar = $timeTemp;
            }else{
                $answer[$k]["created_at"] = null;
            }
//            $answer[$k]->get_from_user_info->id = \Helpers::encrytById($answer[$k]->get_from_user_info);
//            $answer[$k]->get_to_user_info->id = \Helpers::encrytById($answer[$k]->get_to_user_info);
        }
        $total = Answer::where("comment_id",$commentId)->get()->toArray();
        $totalPage = ceil(count($total) / self::$pageSize);
        return $answer;
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
