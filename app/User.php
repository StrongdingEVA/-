<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 关联article 表
     */
    public function user(){
        return $this->hasMany(Article::class);
    }

    public function picHot(){
        return $this->hasMany(Hotpic::class);
    }

    public function userAnwser(){
        return $this->hasMany(Answer::class);
    }

    /**
     * 更新用户查看的帖子
     * @param $articleId
     * @return bool
     */
    public static function updateViews($articleId){
        $userInfo = Auth::user();
        if(!$userInfo){
            return true;
        }
        $userId = $userInfo->id;
        if(!Userextend::getUserExtendById($userId)){
            $arrTemp = array("user_id"=>$userId,"article_collection"=>"","	article_views"=>"");
            Userextend::create($arrTemp);
        }

        $extendInfo = Userextend::getUserExtendById($userId);
        $articleViews = $extendInfo['article_views'] ? json_decode($extendInfo['article_views']) : array();
        if(in_array($articleId,$articleViews)){
            return true;
        }
        DB::beginTransaction();
        $res1 = Article::updateViews($articleId);
        $articleViews[] = $articleId;
        $res2 = Userextend::updateById($userId,array("article_views" => json_encode($articleViews)));
        if($res1 && $res2){
            DB::commit();
            return true;
        }else{dd($extendInfo);
            DB::rollback();
            return false;
        }
    }

    /**
     * 获取用户信息
     * @param $userIdArr array
     */
    public static function getUserInfo($userIdArr){
        if(empty($userIdArr)){
            return array();
        }
        $param = '';
        if(is_string($userIdArr) || is_numeric($userIdArr)){
            $param = array($userIdArr);
        }else{
            $param = $userIdArr;
        }


        $result = self::whereIn("id",$param)->get()->toArray();
        return is_string($userIdArr) ? current($result) : $result;
    }

    /**
     * @param $type 1评论积分  2 发布文章积分 3 登录积分 4 点赞积分 5 取消点赞扣除积分 6 删除评论扣除积分
     * @param bool|true $addOrSub true 增加 false 减少
     * @param string $userId
     * @return mixed
     */
    public static function pointManage($type,$addOrSub = true,$userId = ""){
        $userInfo = Auth::user();
        $userId = $userId ? $userId : $userInfo->id;
        $levelPoint = $userInfo->level_point;
        $point = 0;
        switch($type){
            case 1 :
                $point = COMMENT_POINT;
                break;
            case 2 :
                $point = POST_ARTICLE_POINT;
                break;
            case 3 :
                $point = LOGIN_POINT;
                break;
            case 4 :
                $point = COLLECTION_POINT;
                break;
            case 5 :
                $point = COLLECTION_CANCEL_POINT;
                break;
            case 6 :
                $point = COMMENT_CANCEL_POINT;
                break;
            default :
                $point = 0;
                break;
        }
        $levelPoint = $addOrSub ? $levelPoint + $point : $levelPoint - $point;
        return self::saveInfo(array('id' => $userId),array("level_point"=>$levelPoint));
    }

    public static function saveInfo($where = array(),$param = array()){
        if(!$where || !$param){
            return false;
        }
        return self::where($where)->update($param);
    }
}
