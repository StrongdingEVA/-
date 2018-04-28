<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Article;
use App\Category;
use App\Comment;
use App\Http\Controllers\Auth\AuthController;
use App\Jobs\CollectionBook;
use App\Pic;
use App\User;
use App\Userextend;
use App\UserMessage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        //$this->dispatch((new CollectionBook(17))->delay(2));
        //查询分类
        $fields = array('*');
        $where = array('is_recommend' => 1);
        $whereIn = array();
        $articleList = array();
        if($key == 'friend'){
            if(!$this->uId){
                redirect('/');
            }
            $extInfo = Userextend::getUserExtendById($this->uId);
            $foucs = $extInfo['user_foucs'] ? json_decode($extInfo['user_foucs'],1) : array();
            if(!$foucs){
                return view('Home.index',compact('articleList','key','search'));
            }
            $whereIn = array('user_id',$foucs);
        }

        $search && $where['article_title'] = trim($search);
        switch ($order){
            case 'hot':
                $order = array('comments','desc');
                break;
            case 'new':
                $order = array('create_at','desc');
                break;
            case 'old':
                $order = array('create_at','asc');
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
    public function detail(Request $request,$articleId){
        try{
            self::encrytDeById($articleId);//解密
        }catch (DecryptException $e){
            $message = "你TM有病啊！<br>随便改url里的参数~！<br>报错是你活该！";
            return view("errors.503",compact("message"));
        }

        //更新文章浏览次数
        User::updateViews($articleId);

        $articleInfo = Article::getArticleInfo($articleId);
        $actionLi = $articleInfo->category;
        //推荐的文章
        $articleHistory = self::getArticle($articleInfo->user_id);
        $want = $this->getArticleForCate();
        foreach($want as $ke => $va){
            self::encrytById($want[$ke],"user_id");
            self::encrytById($want[$ke]);
        }

        $articleMastInfo["want"] = $want; //可能想看
        $articleMastInfo["fans"] = AuthController::getUserInfo(UserextendController::useFans($articleInfo->user_id)); // 粉丝的文章
        $articleMastInfo["foucs"] = AuthController::getUserInfo(UserextendController::useFoucs($articleInfo->user_id)); //关注文章

        foreach($articleHistory as $k => $v){
            self::encrytById($articleHistory[$k],"user_id");
            self::encrytById($articleHistory[$k]);
        }

        $articleMastInfo["articleHistory"] = $articleHistory;//发布过的文章

        self::isCollector($articleInfo);//是否收藏
        self::getCollector($articleInfo); //收藏的用户
        self::encrytById($articleInfo);//加密

        $foucsInfo["single"] = UserextendController::isFoucs($articleInfo->getUsername->id); //是否但方面关注
        $foucsInfo["bouth"] = UserextendController::isFoucsBouth($articleInfo->getUsername->id); //是否互相关注
        self::encrytById($articleInfo->getUsername);//加密
        \Helpers::htmlspecdecode($articleInfo,"article_content");

        $perPage = 15;
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }
        $comList = Comment::join("users","users.id","=","comments.user_id")
            ->select("comments.*","users.username","users.logo")->where("article_id",$articleId)->orderBy("id","asc")->get()->toArray();

        $item = array_slice($comList, ($current_page-1)*$perPage, $perPage); //注释1
        $total = count($comList);
        $currentPage = "";
        $paginator = new LengthAwarePaginator($item, $total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);

        $articleComment = $paginator->toArray()['data'];
        foreach($articleComment as $key => $val){
            $totalPage = 0;
            self::encrytById($articleComment[$key],"user_id",1);
            self::encrytById($articleComment[$key],"article_id",1);
            $articleComment[$key]["article_comment"] = htmlspecialchars_decode($val["article_comment"]);
            $articleComment[$key]["answer"] = Answer::getAnswer($val["id"],$totalPage);
            $c = count(Answer::where("comment_id",$val["id"])->get());
            $articleComment[$key]["subAns"] = $c - 5 > 0 ? $c - 5 : 0;
            $articleComment[$key]["totalPage"] = $totalPage;
            $articleComment[$key]["nowPage"] = 1;
        }
        //print_r($articleComment);die;
        return view("Home.detail",compact("userInfo","articleInfo","articleComment","actionLi","foucsInfo","articleMastInfo","paginator"));
    }

    /**
     * 文章详情
     */
    public function detail_2(Request $request,$articleId,$commentId,$comType,$c_id){
        //首页
        $userInfo = Auth::user();

        try{
            self::encrytDeById($articleId);//解密
        }catch (DecryptException $e){
            $message = "你TM有病啊！<br>随便改url里的参数~！<br>报错是你活该！";
            return view("errors.503",compact("message"));
        }

        //更新文章浏览次数
        User::updateViews($articleId);

        //更新消息已读

        UserMessage::where("id","{$c_id}")->update(["status"=>1]);

        $articleInfo = Article::getArticleInfo($articleId);
        $actionLi = $articleInfo->category;
        //推荐的文章
        $articleHistory = self::getArticle($articleInfo->user_id);
        $want = $this->getArticleForCate();
        foreach($want as $ke => $va){
            self::encrytById($want[$ke],"user_id");
            self::encrytById($want[$ke]);
        }

        $articleMastInfo["want"] = $want; //可能想看
        $articleMastInfo["fans"] = AuthController::getUserInfo(UserextendController::useFans($articleInfo->user_id)); // 粉丝的文章
        $articleMastInfo["foucs"] = AuthController::getUserInfo(UserextendController::useFoucs($articleInfo->user_id)); //关注文章

        foreach($articleHistory as $k => $v){
            self::encrytById($articleHistory[$k],"user_id");
            self::encrytById($articleHistory[$k]);
        }

        $articleMastInfo["articleHistory"] = $articleHistory;//发布过的文章

        self::isCollector($articleInfo);//是否收藏
        self::getCollector($articleInfo); //收藏的用户
        self::encrytById($articleInfo);//加密

        $foucsInfo["single"] = UserextendController::isFoucs($articleInfo->getUsername->id); //是否但方面关注
        $foucsInfo["bouth"] = UserextendController::isFoucsBouth($articleInfo->getUsername->id); //是否互相关注
        self::encrytById($articleInfo->getUsername);//加密
        \Helpers::htmlspecdecode($articleInfo,"article_content");

        $perPage = 15;
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }

        $comList = Comment::join("users","users.id","=","comments.user_id")
            ->select("comments.*","users.username","users.logo")->where("article_id",$articleId)->orderBy("id","asc")->get()->toArray();
        //判断当前评论在第几页
        if($comType == 1){
            $k = 0;
            foreach($comList as $key => $val){
                if($val["id"] == $commentId){
                    $k = $key + 1;
                }
            }
            $current_page = ceil($k / $perPage);
        }


        $comList = Comment::join("users","users.id","=","comments.user_id")
            ->select("comments.*","users.username","users.logo")->where("article_id",$articleId)->orderBy("id","asc")->get()->toArray();

        $item = array_slice($comList, ($current_page-1)*$perPage, $perPage); //注释1
        $total = count($comList);
        $currentPage = "";
        $paginator = new LengthAwarePaginator($item, $total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);
        $paginator->setCurrPage($current_page);
        $articleComment = $paginator->toArray()['data'];
        foreach($articleComment as $key => $val){
            $totalPage = 0;
            self::encrytById($articleComment[$key],"user_id",1);
            self::encrytById($articleComment[$key],"article_id",1);
            $articleComment[$key]["article_comment"] = htmlspecialchars_decode($val["article_comment"]);
            $articleComment[$key]["answer"] = $comType == 1 ? Answer::getAnswer($val["id"],$totalPage) : Answer::getAnswer_2($val["id"],$a,$b,$c);
            $articleComment[$key]["totalPage"] = $comType == 1 ? $totalPage : $b;
            $articleComment[$key]["nowPage"] = $comType == 1 ? 1 : $c;
        }
        $arrFind = array($commentId,$comType);
        //print_r($articleComment);die;
        return view("Home.detail",compact("userInfo","articleInfo","articleComment","actionLi","foucsInfo","articleMastInfo","paginator","arrFind"));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 发布文章的页面
     */
    public function article(){
        $userInfo = Auth::user();
        $cateInfo = Category::where("level",0)->orderBy("id","asc")->get();
        $actionLi = 90;
        return view("Home.article",compact("userInfo","cateInfo","actionLi"));
    }

    /**
     * 获取文章的点赞者
     */
    public static function getCollector(&$articleInfo){
        if(!$articleInfo){
            $articleInfo->collector = array();
            return;
        }
        $arrTemp = array();
        $articleColletor = $articleInfo->collector ? json_decode($articleInfo->collector) : array();
        foreach($articleColletor as $key => $val){
            $arrTemp[] = User::where("id",$val)->first();
            if($key > 10){
                break;
            }
        }
        $articleInfo->collector = $arrTemp;
    }

    /**
     * 判断当前用户是否点赞文章
     * @param $articleInfo
     * @return array
     */
    public static function isCollector(&$articleInfo){
        if(!$articleInfo){
            return array();
        }
        $arrTemp = array();
        $userInfo = Auth::user();
        if(!$userInfo){
            return false;
        }
        $articleColletor = $articleInfo->collector ? json_decode($articleInfo->collector) : array();
        in_array($userInfo->id,$articleColletor) ? $articleInfo->isCollector = 1 : $articleInfo->isCollector = 0;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 发布文章
     */
    public function createArticle(Request $request){
        $articleInfo = $request->all();
        $validator = $this->validator($articleInfo);
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $articleInfo["article_content"] = $articleInfo["editorValue"];
        $articleInfo["user_id"] = Auth::user()->id;
        $article = new Article();
        $article->category = $articleInfo["category"];
        $article->article_title = htmlspecialchars($articleInfo["article_title"]);
        $article->article_disc = $articleInfo["article_disc"];
        $article->article_content = htmlspecialchars($articleInfo["article_content"]);
//        $article->article_thumb = \Helpers::getThumbFromVideo($articleInfo["article_video"]);
        $article->article_thumb = $articleInfo["article_thumb"];
        $article->article_source_pic = str_replace("thumb","source",$articleInfo["article_thumb"]);
        $article->article_video = $articleInfo["article_video"];
        $article->user_id = $articleInfo["user_id"];
        $article->is_recommend = isset($articleInfo["is_show"]) ? 1 : 0;

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
            'category' => 'required',
//            'article_thumb' => 'required|max:255',
            'article_disc' => 'required|max:255',
            'editorValue' => 'required',
        ]);
    }

    /**
     * 获取首页滚动文章
     * 首先获取推荐文章 如果没有推荐文章则选择今日浏览次数最多的文章
     */
    public function getSrollArticle(){
        $result = Article::join("users","users.id","=","articles.user_id")
            ->select("articles.*","users.username")->where("articles.is_recommend",1)->get()->toArray();

        if(!count($result)){
            $result = Article::join("users","users.id","=","articles.user_id")
                ->select("articles.*","users.username")->orderBy("articles.views","asc")->paginate(5);
        }
        foreach($result as $k => $v){
            $this->encrytById($result[$k]);
        }
        return $result;
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
     * 更新文章评论次数
     * @param $articleId  文章ID
     * @param int $add  ture 加 false 减
     */
    public static function updateArticleComment($articleId,$add = 1){
        return $add ? Article::where("id","{$articleId}")->increment("comments",1) : Article::where("id","{$articleId}")->decrement("comments",1);
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
     * 获取用户发布的文章信息
     * @param string $userId
     */
    public static function getArticle($userId = ""){
        return $userId ? Article::where(["user_id"=>$userId,"is_show"=>1])->orderBy("id","desc")->paginate(5) : Article::where(["user_id"=>Auth::user()->id,"is_show"=>1])->orderBy("id","desc")->paginate(5);
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

    /**
     * 判读文章是否被改用户评论
     * @param $articleId 文章ID
     * @param string $userId 用户ID
     * @return int
     */
    public static function judgeComment($articleId,$userId = ""){
        $userId = $userId ? $userId : Auth::user()->id;
        $commentInfo = Comment::where(["article_id"=>$articleId,"user_id"=>$userId])->get();
        return count($commentInfo);
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
