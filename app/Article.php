<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
            return array();
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
        return $result;
    }
}
