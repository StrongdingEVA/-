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

    public function run($that,$server,$data){
        $f = $data['f'];
        switch ($f){
            case 's_m_t_u':
                $this->sendMsgToUsers($that,$server,$data);
                break;
            case 'c_r':
                $this->createRoom();
                break;
            default:
                $this->sendMsgToUsers($that,$server,$data);
                break;
        }
    }

    public function sendMsgToUsers($that,$server,$data){
        $fd = $data['recepter'];
        $msg = $data['msg'];
        $server->push($fd, $msg);
    }

    public function createRoom($that,$server,$data){
        $param = array(
            'room_name' => '测试房间名',
            'room_belong' => 10095,
            'uinfo' => array(
                'user_id' => 10095,
                'username' => 'mrtin',
                'logo' => '/Uploads/2018-04-05/212121.png',
                'gental' => 1,
                'server' => 'server',
                'fd' => '1',
            ),
        );
        $room = new Room($param);
        $that->addRoom($room);
        $server->push($data['fd'], $that);
    }
}