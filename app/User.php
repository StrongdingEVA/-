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
        if(!Userextend::where("user_id",$userId)->first()){
            $arrTemp = array("user_id"=>$userId,"article_collection"=>"","	article_views"=>"");
            Userextend::create($arrTemp);
        }

        $extendInfo = Userextend::where("user_id",$userId)->first();
        $articleViews = $extendInfo->article_views ? json_decode($extendInfo->article_views) : array();
        if(in_array($articleId,$articleViews)){
            return true;
        }
        Article::where("id",$articleId)->increment("views",1);
        $articleViews[] = $articleId;
        return Userextend::where("user_id",$userId)->update(["article_views" => json_encode($articleViews)]);
    }
}
