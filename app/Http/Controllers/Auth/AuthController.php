<?php

namespace App\Http\Controllers\Auth;

use App\Answer;
use App\Article;
use App\Comment;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PointrecordController;
use App\Http\Controllers\SendrecordController;
use App\Http\Controllers\UserextendController;
use App\Http\Controllers\UserMessageController;
use App\Pointrecord;
use App\User;
use App\Http\Requests;
use App\Userextend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if($_SERVER["REQUEST_URI"] == "/auth/register"){
            return Validator::make($data, [
                'username' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);
        }else{
            return Validator::make($data, [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * 更新用户最后登录时间和增加等级积分
     */
    public static function updateInfoByLogin(){
        $userInfo = Auth::user();
        if(!count($userInfo)){
            return false;
        }
        $levelPoint = $userInfo["level_point"];
        $updateAt = substr($userInfo["last_login"],0,10);
        $dateNow = date("Y-m-d",time());
        if($updateAt != $dateNow){
            DB::table("users")->where('id', $userInfo["id"])
                ->update(['level_point' => $levelPoint + 10]);
            User::pointManage(3,true);
            PointrecordController::insertRecord(3);
        }
        DB::table("users")->where('id', $userInfo["id"])
            ->update(['last_login' => date("Y-m-d H:i:s",time())]);
    }

    /**
     * @param $userId
     * @param $number
     * 设置邮箱验证码
     */
    public function setCheckNum($userId,$number){
        return (DB::table("users")->where('id', $userId)
            ->update(['valid_num' => $number,'num_send_time'=>time()]));
    }

    public function sendEmail($email,$number){
        if(empty($email) || empty($number)){
            return false;
        }
        $this->email = $email;
        $content = "你正在注册Mr.Tin Blog，此次验证码为{$number}";
        return (Mail::raw($content,function ($message) {
            $message ->to($this->email)->subject('Mr.Tin Blog 注册');
        }));
    }

    /**
     * 发送验证码到邮箱
     * @param Request $request
     */
    public function getCheckNumber(Request $request){
        $arrOut = array("status" => -1,"message" => "注册失败",);
        $number = rand(1954,9999);
        //发送验证码
        if($this->sendEmail($_POST["email"],$number)){
            $recordId = SendrecordController::insertRecord($_POST["email"],$number);
            $arrOut["status"] = 1;
            $arrOut["record"] = $recordId;
            $arrOut["message"] = "验证码已经发送至邮箱，请注意查收~！";
        }
        echo json_encode($arrOut);return;
    }

    /**
     * 文章评论
     * @param Request $request
     */
    public function userComment(Request $request){
        $articleId = htmlspecialchars($request->get("articleid",''));
        if(!$articleId){
            Redirect::back()->withInput($_POST);
        }
        $articleIdEn = $articleId;
        $content = \Helpers::clearStr($request->get("editorValue"));
        $articleComment = $content ? $content : Redirect::back()->withInput($_POST);
        ArticleController::encrytDeById($articleId);
        $userInfo = Auth::user();
        $arrTemp = array("article_id"=>$articleId,"user_id"=>$userInfo->id,"article_comment"=>$articleComment);
        $articleInfo = Article::getArticleInfo($articleId);
        //开启事务
        DB::beginTransaction();
        //插入评论信息
        $res_1 = Comment::create($arrTemp);
        //更新文章评论次数
        $res_2 = Article::updateArticleComment($articleId,1);
        if($userInfo->id != $articleInfo['get_username']['id']){ //判断是否是本人评论自己的文章，如果不是则插入信息表
            $param = array(
                'user_id' => $userInfo->id,
                'type' => 1,
                'disc' => "{$userInfo->username}评论了你的文章~",
                'article_id' => $articleId,
                'com_type' => 1,
                'ans_id' => 0,
                'comment_id' => $res_1->id,
            );
            $res_5 = UserMessageController::create($param);
        }else{
            $res_5 = true;
        }

        if(Comment::judgeComment($articleId)){
            $res_3 = true;
            $res_4 = true;
        }else{
            $res_3 = User::pointManage(1,true);
            $res_4 = PointrecordController::insertRecord(1);
        }

        if(!$res_1 || !$res_2 || !$res_3 || !$res_4 || !$res_5){
            DB::rollback();
            return Redirect::back()->withInput(array("error"=>"评论失败","editorValue"=>$_POST["editorValue"]));
        }
        DB::commit();
        return redirect("/article_detail/{$articleIdEn}");
    }

    /**
     * 用户回复
     * @param Redirect $request
     * @return Redirect
     */
    public function userAnswer(Request $request){
        $userInfo = Auth::user();
        $articleId = $request->get("article_id");
        $commentId = $request->get("comment_id");
        $toUserId = $request->get("to_user_id");
        $content = \Helpers::clearStr($request->get("article_comment"));
        $answerArr = array(
            "article_comment" => $content,
            "article_id" => $articleId,
            "comment_id" => $commentId,
            "to_user_id" => $toUserId,
            "from_user_id" => $userInfo->id,
        );

        DB::beginTransaction();
        $res_1 = Answer::create($answerArr);
        if(Auth::user()->id != $toUserId){
            $param = array(
                'user_id' => $userInfo->id,
                'type' => 1,
                'disc' => "{$userInfo->username}回复了你的评论~",
                'article_id' => $articleId,
                'com_type' => 2,
                'ans_id' => $res_1->id,
                'comment_id' => $commentId,
            );
            $res_2 = UserMessageController::create($param);
        }else{
            $res_2 = true;
        }
        if(!$res_1 || !$res_2){
            DB::rollback();
            Redirect::back();
            \Helpers::echoJsonAjax(-1,'评论失败');
        }
        DB::commit();
        \Helpers::echoJsonAjax(0,'评论成功');
    }

    /**
     * 更新用户收藏
     */
    public function userColletion(Request $request){
        $articleId = $request->get('article_id',0);
        if(!$articleId){
            \Helpers::echoJsonAjax(-1,"收藏失败");
        }

        DB::beginTransaction();
        if(UserextendController::updateCollect($articleId) == -1){
            \Helpers::echoJsonAjax(-1,"已经收藏过了");
        }
        $res_1 = ArticleController::updateArticleClont($articleId);
        $res_2 = ArticleController::updateArticleCollertor($articleId,1);
        $res_3 = User::pointManage(4,true);
        $res_4 = PointrecordController::insertRecord(4);
        if(!$res_1 || !$res_2 || !$res_3 || !$res_4){
            DB::rollBack();
            \Helpers::echoJsonAjax(-1,"收藏失败");
        }
        DB::commit();
        $articleInfo = Article::getArticleInfo($articleId);
        \Helpers::echoJsonAjax(1,"收藏成功",$articleInfo['collections'],0);
    }

    /**
     * 用户取消收藏
     */
    public function userColletionCancel(Request $request){
        $articleId = $request->get('article_id',0);
        if(!$articleId){
            \Helpers::echoJsonAjax(-1,"取消收藏失败");
        }

        ArticleController::encrytDeById($articleId);
        if(UserextendController::updateCollect($articleId,0) == -1){
            \Helpers::echoJsonAjax(-1,"还未收藏，不能取消收藏");
        }
        DB::beginTransaction();
        $res_1 = ArticleController::updateArticleClont($articleId,0);
        $res_2 = ArticleController::updateArticleCollertor($articleId,0);
        $res_3 = User::pointManage(5,false);
        $res_4 = PointrecordController::insertRecord(5);
        if(!$res_1 || !$res_2 || !$res_3 || !$res_4){
            DB::rollback();
            \Helpers::echoJsonAjax(1,"取消收藏失败");
        }
        DB::commit();
        $articleInfo = Article::getArticleInfo($articleId);
        \Helpers::echoJsonAjax(1,"取消收藏成功",$articleInfo['collections'],0);
    }

    /**
     * 关注用户
     * @param Request $request
     */
    public function foucsUser(Request $request,$userId){
        if(!$userId){
            \Helpers::echoJsonAjax(-1,"参数错误");
        }

        $userIdNow = @Auth::user()->id;
        if(!$userIdNow){
            $uri = substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"article_detail") -1);
            $request->session()->put('redUrlAuto', $uri);
            $arrOut = array("status"=>-2,"message"=>"请先登录...");
            echo json_encode($arrOut);return;
        }
        if($userIdNow == $userId){
            \Helpers::echoJsonAjax(-1,"你TM关注自己干吊...");
        }
        if(Userextend::isFoucs($userId)){
            \Helpers::echoJsonAjax(-1,"你已经关注过他了...");
        }
        $userInfoTo = User::getUserInfo($userId);
        if(empty($userInfoTo)){
            \Helpers::echoJsonAjax(-1,"不存在该用户...");
        }
        $userFoucs = Userextend::useFoucs();
        $userFoucs[] = $userId;

        DB::beginTransaction();
        $res_1 = Userextend::updateById($userIdNow,["user_foucs"=>json_encode($userFoucs)]);
        $userFoucsTo = Userextend::useFans($userId);//被关注用户的粉丝信息
        $userFoucsTo[] = $userIdNow;
        $res_2 = Userextend::updateById($userId,["user_fans"=>json_encode($userFoucsTo)]);
        if(!$res_1 || !$res_2){
            DB::rollback();
            \Helpers::echoJsonAjax(1,"关注失败~!");
        }
        DB::commit();
        \Helpers::echoJsonAjax(1,"关注成功~!",Userextend::isFoucsBouth($userId),0);
    }

    /**
     * 取消关注用户
     * @param Request $request
     */
    public function foucsUserCancle(Request $request,$userId){
        if(!$userId){
            \Helpers::echoJsonAjax(-1,"参数错误");
        }
        ArticleController::encrytDeById($userId);
        $userIdNow = @Auth::user()->id;
        if(!$userIdNow){
            $uri = substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"article_detail") -1);
            $request->session()->put('redUrlAuto', $uri);
            $arrOut = array("status"=>-2,"message"=>"请先登录...");
            echo json_encode($arrOut);return;
        }
        if(!UserextendController::isFoucs($userId)){
            \Helpers::echoJsonAjax(-1,"你并没有关注他，取消个毛...");
        }
        $userInfoTo = User::where("id",$userId)->first();
        if(empty($userInfoTo)){
            \Helpers::echoJsonAjax(-1,"不存在该用户...");
        }
        $userFoucs = UserextendController::useFoucs();
        unset($userFoucs[array_search($userId,$userFoucs)]);
        DB::beginTransaction();
        $res_1 = Userextend::where("user_id",$userIdNow)->update(["user_foucs"=>json_encode($userFoucs)]);
        $userFoucsTo = UserextendController::useFans($userId);//被关注用户的粉丝信息
        unset($userFoucsTo[array_search($userIdNow,$userFoucsTo)]);
        $res_2 = Userextend::where("user_id",$userId)->update(["user_fans"=>json_encode($userFoucsTo)]);
        if(!$res_1 || !$res_2){
            DB::rollback();
            \Helpers::echoJsonAjax(-1,"取消关注失败~!");
        }
        DB::commit();
        \Helpers::echoJsonAjax(1,"取消关注成功~!");
    }


    public function sendMsgGet(Request $request,$uid){
        $userInfo = User::getUserInfo($uid);
        return view('Home.sendmsg',compact("userInfo"));
    }

    public function sendMsgPost(Request $request){
        $arrRequest = $request->all();
        $arr = array();
        $arr["toUserId"] = $arrRequest["toUserId"];
        $arr["fromUserId"] = Auth::user()->id;
        $arr["message"] = $arrRequest["message"];
        $arr["messageType"] = $arrRequest["messageType"];
        $arr["pubserType"] = $arrRequest["pubserType"];
        
        \Illuminate\Support\Facades\Event::fire(new \App\Events\SomeEvent(json_encode($arr)));
        return 1;
    }


    /**
     * 检验用户
     */
    public static function checkUser(){
        return empty(Auth::user()) ? 0 : 1;
    }

    public function checkIsLogin(){
        if(empty(Auth::user())){
            \Helpers::echoJsonAjax(-1,'请先登录');
        }else{
            \Helpers::echoJsonAjax(0);
        }
    }
}
