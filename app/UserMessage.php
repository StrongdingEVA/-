<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class UserMessage extends Model
{
    //
    protected $fillable = ['from_id','to_id', 'type','disc','article_id','status','ans_id','com_type','comment_id'];

    /**
     * 获取信息
     * @param $userId
     * @param int $status
     * @return mixed
     */
    public static function getMsgByToUid($userId,$status = 0){
        $info = json_decode(Redis::get(USER_MSG . $userId),1);
        if(!$info){
            $info = self::where(["to_id"=>$userId,"status"=>$status])->orderBy("created_at","desc")->get()->toArray();
            Redis::set(USER_MSG . $userId,json_encode($info));
        }
        return $info;
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
