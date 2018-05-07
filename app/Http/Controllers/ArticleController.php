<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Article;
use App\Comment;
use App\Http\Controllers\Auth\AuthController;
use App\User;
use App\Userextend;
use App\UserMessage;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ArticleController extends BaseController
{
    public $arrOut = array("status" => -1,"message" => "参数错误");

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request,$key = 'world',$order = 'hot',$search = '')
    {
        Redis::set('testkye','test value');
        //$this->dispatch((new CollectionBook(17))->delay(2));
        //查询分类
        $fields = array('*');
        $where = array('is_show' => 1);
        $whereIn = array();
        $articleList = array();
        if($key == 'friend'){
            if(!$this->uId){
                return redirect('auth/login');exit();
            }
            $extInfo = Userextend::getUserExtendById($this->uId);
            $foucs = $extInfo['user_foucs'] ? json_decode($extInfo['user_foucs'],1) : array();
            if(!$foucs){
                return view('Home.index',compact('articleList','key','search'));
            }
            $whereIn = array('user_id',$foucs);
        }else if($key == 'own'){
            if(!$this->uId){
                return redirect('auth/login');exit();
            }
            $whereIn = array('user_id',[$this->uId]);
        }

        $search && $where['article_title'] = trim($search);
        switch ($order){
            case 'hot':
                $order = array('comments','desc');
                break;
            case 'new':
                $order = array('created_at','desc');
                break;
            case 'old':
                $order = array('created_at','asc');
                break;
            default:
                $order = array('comments','desc');
                break;
        }

        $data = Article::getList($fields,$where,$whereIn,$order,1,16);
        $articleList = $data ? $data['data'] : array();
        return view('Home.index',compact('articleList','key','search'));
    }

    /**
     * 文章详情
     */
    public function detail(Request $request,$id,$cid = '',$aid = '',$type = ''){
        //更新文章浏览次数
        User::updateViews($id);

        $cid && UserMessage::updateById($cid,["status"=>1]);

        $articleInfo = Article::getArticleInfo($id);

        $articleMastInfo["want"] =  Article::getArticleForHot(); //推荐最近热门文章
        $articleMastInfo["fans"] = User::getUserInfo(Userextend::useFans($articleInfo['user_id'])); //获取文章发布者的粉丝信息
        $articleMastInfo["foucs"] = User::getUserInfo(Userextend::useFoucs($articleInfo['user_id'])); //获取文章发布者的关注
        $articleMastInfo["articleHistory"] = Article::getHistArticle($articleInfo['user_id']); //获取该文章作者最近发布记录

        self::isCollector($articleInfo);//是否收藏
        self::getCollector($articleInfo); //收藏的用户

        $foucsInfo["single"] = Userextend::isFoucs($articleInfo['user_id']); //是否但方面关注
        $foucsInfo["bouth"] = Userextend::isFoucsBouth($articleInfo['user_id']); //是否互相关注
        \Helpers::htmlspecdecode($articleInfo,"article_content");

        $perPage = 10;
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }

        $result = Comment::getCommentList($cid,$id,array('comments.article_id' => $id),array('comments.id','asc'),$current_page,$perPage);

        $articleComment = $result['data'];
        $paginator = $result['paginator'];
        foreach($articleComment as $key => $val){
            self::encrytById($articleComment[$key],"user_id",1);
            self::encrytById($articleComment[$key],"article_id",1);
            $articleComment[$key]["article_comment"] = htmlspecialchars_decode($val["article_comment"]);
            $ansList = $cid == $val['id'] ? Answer::getAnswerByComment($val["id"],$aid,1,5) : Answer::getAnswerByComment($val["id"],0,1,5);
            foreach($ansList['data'] as $k => $v){
                $ansList['data'][$k]["article_comment"] = htmlspecialchars_decode($v["article_comment"]);
            }
            $articleComment[$key]["answer"] = $ansList['data'];
            $articleComment[$key]["subAns"] = $ansList['sub'];
            $articleComment[$key]["totalPage"] = $ansList['totalPage'];
            $articleComment[$key]["nowPage"] = $ansList['nowPage'];
        }
        if($type == 2){//回复
            $arrFind = array($aid,$type);
        }else if($type == 1){//评论
            $arrFind = array($cid,$type);
        }

        return view("Home.detail",compact("userInfo","articleInfo","articleComment","actionLi","foucsInfo","articleMastInfo","paginator","arrFind"));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 发布文章的页面
     */
    public function article(){
        return view("Home.article");
    }

    /**
     * 获取文章的点赞
     */
    public static function getCollector(&$articleInfo){
        if(!$articleInfo){
            return;
        }
        $arrTemp = array();
        $articleColletor = $articleInfo['collector'] ? json_decode($articleInfo['collector'],1) : array();
        User::getUserInfo($articleColletor);
        $articleInfo['collector'] = $arrTemp;
    }

    /**
     * 判断当前用户是否点赞文章
     * @param $articleInfo
     * @return array
     */
    public static function isCollector(&$articleInfo){
        if(!$articleInfo){
            return;
        }
        $userInfo = Auth::user();
        if(!$userInfo){
            $articleInfo['isCollector'] = 0;
            return false;
        }
        $articleColletor = $articleInfo['collector'] ? json_decode($articleInfo['collector']) : array();
        in_array($userInfo['id'],$articleColletor) ? $articleInfo['isCollector'] = 1 : $articleInfo['isCollector'] = 0;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 发布文章
     */
    public function createArticle(Request $request){
        $articleInfo = $request->all();
        if(mb_strlen($articleInfo['editorValue']) > 10){
            $articleInfo['article_disc'] = substr(strip_tags($articleInfo['editorValue']),0,10) . '...';
        }else{
            $articleInfo['article_disc'] = $articleInfo['editorValue'];
        }
        $validator = $this->validator($articleInfo);
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $articleInfo["article_content"] = $articleInfo["editorValue"];
        $articleInfo["user_id"] = Auth::user()->id;
        $article = new Article();
        $article->article_title = htmlspecialchars($articleInfo["article_title"]);
        $article->article_disc = $articleInfo["article_disc"];
        $article->article_content = htmlspecialchars($articleInfo["article_content"]);
        $article->article_thumb = substr($articleInfo["article_thumb"],0,strpos($articleInfo["article_thumb"],','));
        $article->article_source_pic = str_replace("thumb","source",$articleInfo["article_thumb"]);
        $article->user_id = $articleInfo["user_id"];
        $article->is_recommend = 0;
        $article->is_show = isset($articleInfo["is_show"]) ? 1 : 0;

        DB::beginTransaction();
        $res_1 = $article->save($articleInfo);
        $res_2 = AuthController::pointManage(2);
        $res_3 = PointrecordController::insertRecord(2);
        $res_4 = User::where("id",$articleInfo["user_id"])->increment("post_count",1);
        if(!$res_1 || !$res_2 || !$res_3 || !$res_4){
            DB::rollback();
            return Redirect::back()->withInput($articleInfo);

        }
        DB::commit();

        //异步延迟执行
        //$this->dispatch((new CollectionBook($article->id))->delay(1));
        return redirect('/');
    }

    /**
     * 上传图片
     * @param Request $request
     */
    public function uploadimg(Request $request){
        $result = \Helpers::uploadimg("","upload/articleimg",2);//echo json_encode($result);die;
        if($result["status"]==0){
            $imgDst = str_replace("source","thumb",$result["result"]);
            \Helpers::resizejpg($result["result"],"./".$imgDst,0,200);

            $this->arrOut["status"] = 1;
            $this->arrOut["message"] = "上传成功";
            $this->arrOut["src"] = "/".$imgDst;
        }else{
            $this->arrOut["status"] = -1;
            $this->arrOut["message"] = $result["message"];
        }
        echo json_encode($this->arrOut);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'article_title' => 'required|max:255',
            'article_disc' => 'required|max:255',
            'editorValue' => 'required',
        ]);
    }

    /**
     * 获取分类文章 （用户点赞次数最多的）
     * @type 类别ID
     */
    public static function getDaily($type){
        $articleInfo = Article::where(["article_status"=>"0","category"=>$type])->where("created_at",">=","DATE_ADD(LEFT(NOW(),10),INTERVAL -3 DAY)")->get()->toArray();
        $arrTemp = array();
        foreach($articleInfo as $k => $v){
            $collector = $v["collector"] ? json_decode($v["collector"]) : array();
            $arrTemp[$v["id"]] = count($collector);
        }
        arsort($arrTemp);
        $arrArticleTemp = array();
        foreach($arrTemp as $key => $val){
            $articleInfo = Article::where("id",$key)->with("getUsername")->first()->toArray();
            self::encrytById($articleInfo,"id");
            $arrArticleTemp[] = $articleInfo;
        }
        return $arrArticleTemp;
    }

    /**
     * 给文章ID加密
     */
    public static function encrytById(&$item,$key = "id",$type = 0){
//        if($type){
//            $item[$key] = Crypt::encrypt($item[$key]);
//        }else{
//            $item->$key = Crypt::encrypt($item->$key);
//        }
    }

    /**
     * 给文章ID解密
     */
    public static function encrytDeById(&$articleId){
//        $articleId = Crypt::decrypt($articleId);//解密
    }

    /**
     * 更新文章浏览次数
     * @param $articleId  文章ID
     * @param int $add  ture 加 false 减
     */
    public static function updateArticleView($articleId,$add = 1){
        return $add ? Article::where("id","{$articleId}")->increment("views",1) : Article::where("id","{$articleId}")->decrement("views",1);
    }

    /**
     * 更新文章点赞次数
     * @param $articleId  文章ID
     * @param int $add  ture 加 false 减
     */
    public static function updateArticleClont($articleId,$add = 1){
        return $add ? Article::where("id","{$articleId}")->increment("collections",1) : Article::where("id","{$articleId}")->decrement("collections",1);
    }

    /**
     * 更新文章收藏者
     * @param $articleId
     * @param int $add
     * @return mixed
     */
    public static function updateArticleCollertor($articleId,$add = 1){
        $userInfo = Auth::user();
        $articleInfo = Article::where("id",$articleId)->first();
        $collector = $articleInfo->collector ? json_decode($articleInfo->collector,1) : array();
        if($add){
            if(!array_search($userInfo->id,$collector)){
                $collector[] = $userInfo->id;
            }
        }else{
            if(isset($collector[array_search($userInfo->id,$collector)])){
                unset($collector[array_search($userInfo->id,$collector)]);
            }
        }
        return Article::where("id","{$articleId}")->update(["collector" => json_encode($collector)]);
    }


    /**
     * 根据文章类型返回推荐文章
     * @param $cateArr
     */
    public function getArticleForCate($userId = ""){
        $userId = $userId ? $userId : @Auth::user()->id;
        $articleType = array();
        if($userId){
            $articleType = Userextend::getFrequencyCate($userId);
        }
        if(!$articleType || $userId){
            return Article::where(["article_status"=>0,"is_show"=>1])->where("created_at",">=","DATE_ADD(LEFT(NOW(),10),INTERVAL -10 DAY)")->orderBy("id","desc")->paginate(4);
        }
        return Article::whereIn("category",$articleType)->where(["article_status"=>0,"is_show"=>1])->where("created_at",">=","DATE_ADD(LEFT(NOW(),10),INTERVAL -10 DAY)")->orderBy("id","desc")->paginate(4);
    }

    public function test(Request $request){
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1',6379,30);
//        $redis->auth('mrtin');
//        $result = $redis->get('name');
//        var_dump($result);
        phpinfo();
    }


    public function linshi(){
    	return view("Home.linshi");
    }


    public function test_server(){
        require app_path() . '/helper/SwooleServer.php';
        $server = new SwooleServer();
    }

    public function test_client(){
        require app_path() . '/helper/SwooleClient.php';
        $client = new SwooleClient();
        $client->connect();
    }
}
