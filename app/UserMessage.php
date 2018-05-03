<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    //
    protected $fillable = ['from_id','to_id', 'type','message_disc','article_id','status','ans_id','comtype','comment_id'];

    /**
     * 获取信息
     * @param $userId
     * @param int $status
     * @return mixed
     */
    public static function getMsgByToUid($userId,$status = 0){
        return self::where(["to_id"=>$userId,"status"=>$status])->orderBy("created_at","desc")->get()->toArray();
    }

    /**
     * 更新消息状态
     * @param $msgId
     * @param $param
     * @return bool
     */
    public static function updateById($msgId,$param){
        if(!$msgId){
            return false;
        }
        return self::where("id","{$msgId}")->update($param);
    }
}
