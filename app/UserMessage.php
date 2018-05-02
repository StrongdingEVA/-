<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    //
    protected $fillable = ['user_id', 'type','message_disc','article_id','status','ans_id','comtype','comment_id'];

    /**
     * 获取信息
     * @param $userId
     * @param int $status
     * @return mixed
     */
    public static function getMsgByUid($userId,$status = 0){
        return self::where(["user_id"=>$userId,"status"=>$status])->orderBy("created_at","desc")->get()->toArray();
    }
}
