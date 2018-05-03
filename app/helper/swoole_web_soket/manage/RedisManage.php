<?php

namespace manage;
use Illuminate\Support\Facades\Redis;

/**
 * redis队列入库本地运行脚本
 */
class RedisManage{

    var $redis;

    function __construct() {
        $this->redis = new Redis();
    }

    public function addTask($cid = 0,$name){
        if(!$cid){
            return false;
        }
        return $this->redis->setqueue($name,$cid);
    }

    public function useTask($name = ''){
        if(!$name){
            return false;
        }
        return $this->redis->rPop($name);
    }
}
