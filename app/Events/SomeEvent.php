<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SomeEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $toUserId = ""; //信息接受者ID

    public $fromUserId = ""; //信息发送者ID 管理员 0

    public $message = ""; //信息内容

    public $messageType = "1"; //信息类型 1 文字 2 图片 3 表情
 
    public $pubserType = ""; //信息接收对象类型  1 所有用户 2 单个用户

    public $userInfo = ""; //信息发送者的userInfo

    public $sendTime = ""; //发送时间
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($paraJson)
    {
        //
        // file_put_contents('d://aaaaaa.txt', $paraJson);
        if(!is_string($paraJson)){
            return false;
        }
        $paraArr = json_decode($paraJson,1);

        if(!is_array($paraArr)){
            return false;
        }
        if(!isset($paraArr["toUserId"]) || !isset($paraArr["message"]) || !isset($paraArr["messageType"]) || !isset($paraArr["pubserType"]) || !isset($paraArr["fromUserId"])){
            return false;
        }
    
        $this->toUserId = $paraArr["toUserId"];
        $this->fromUserId = $paraArr["fromUserId"];
        $this->message = $paraArr["message"];
        $this->messageType = $paraArr["messageType"];
        $this->pubserType = $paraArr["pubserType"];
        $this->userInfo = User::where("id",$paraArr["fromUserId"])->first();
        $this->sendTime = date("Y/m/d H:i:s",time());
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ["{$this->toUserId}"];
    }
}
