<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:43
 */
use autoload\Autoload;

error_reporting(E_ALL);
$rootPath = __DIR__;
require 'autoload/autoload.php';
Autoload::run($rootPath);

class WebsocketTest {
    public $server;
    public function __construct() {
        $this->server = new swoole_websocket_server("0.0.0.0", 11223);

//        $this->server->set(array('task_worker_num' => 8));
//
//        $this->server->on('task',function(swoole_websocket_server $server,$task_id,$from_id, $data){
//            echo "This Task {$task_id} from Worker {$from_id}\n";
//            echo "Data: {$data}\n";
//            for($i = 0 ; $i < 10 ; $i ++ ) {
//                sleep(1);
//                echo "Task {$task_id} Handle {$i} times...\n";
//            }
//            $fd = json_decode( $data , true )['fd'];
//            $server->push( $fd , "Data in Task {$task_id}");
//            return "Task {$task_id}'s result";
//        });
//
//        $this->server->on('finish',function($server,$task_id, $data){
//            echo "Task {$task_id} finish\n";
//            echo "Result: {$data}\n";
//        });

        $this->server->on('open', function (swoole_websocket_server $server, $request) {;
            $temp = array('info' => '当前链接人数','data' => $this->server->connections);
            $server->push($request->fd,json_encode($temp));
            echo "server: handshake success with fd{$request->fd}\n";
        });
        $this->server->on('message', function (swoole_websocket_server $server, $frame) {
            $data = json_decode($frame->data,1);
            echo "receive from 111 {$frame->fd}; act:{$data['act']}; data:{$data['data']},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });
        $this->server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });
        $this->server->on('request', function ($request, $response) {
            // 接收http请求从get获取message参数的值，给用户推送
            // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
            foreach ($this->server->connections as $fd) {
                $this->server->push($fd, $request->get['message']);
            }
        });
        $this->server->start();
    }
}

new WebsocketTest();