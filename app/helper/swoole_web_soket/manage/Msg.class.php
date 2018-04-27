<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 16:53
 */
namespace manage;
class Msg{
    public function __construct(){
    }

    public function run($that,$data){
        $fd = $data['recepter'];
        $msg = $data['msg'];
        $that->server->push($fd, $msg);return;
        $f = $data['f'];
        switch ($f){
            case 's_m_t_u':
                $this->sendMsgToUsers($server,$data);
                break;
            default:
                $this->sendMsgToUsers($server,$data);
                break;
        }
    }

    public function sendMsgToUsers($server,$data){
        $fd = $data['recepter'];
        $msg = $data['msg'];
        $server->push($fd, $msg);
    }
}