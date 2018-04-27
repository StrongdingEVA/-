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
    public $connecter = '';
    public function __construct() {
        $this->server = new swoole_websocket_server("0.0.0.0", 11223);

        $this->server->set(array('task_worker_num' => 8));

        $this->server->on('task',function(swoole_websocket_server $server,$task_id,$from_id, $data){
            echo "This Task {$task_id} from Worker {$from_id}\n";
            echo "Data: {$data['data']}\n";
            for($i = 0 ; $i < 10 ; $i ++ ) {
                sleep(1);
                echo "Task {$task_id} Handle {$i} times...\n";
            }
            $fd = $data['fd'];
            $server->push( $fd , "Data in Task {$task_id}");
            return "Task {$task_id}'s result";
        });

        $this->server->on('finish',function($server,$task_id, $data){
            echo "Task {$task_id} finish\n";
            echo "Result: {$data}\n";
        });

//        $this->server->on('handshake', function (swoole_http_request $request, swoole_http_response $response) {
//            // websocket握手连接算法验证
//            $secWebSocketKey = $request->header['sec-websocket-key'];
//            $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
//            if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
//                $response->end();
//                return false;
//            }
//            echo $request->header['sec-websocket-key'];
//            $key = base64_encode(sha1(
//                $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
//                true
//            ));
//
//            $headers = [
//                'Upgrade' => 'websocket',
//                'Connection' => 'Upgrade',
//                'Sec-WebSocket-Accept' => $key,
//                'Sec-WebSocket-Version' => '13',
//            ];
//
//            // WebSocket connection to 'ws://127.0.0.1:9502/'
//            // failed: Error during WebSocket handshake:
//            // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
//            if (isset($request->header['sec-websocket-protocol'])) {
//                $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
//            }
//
//            foreach ($headers as $key => $val) {
//                $response->header($key, $val);
//            }
//
//            $response->status(101);
//            $response->end();
//              return true;
//        });

        $this->server->on('open', function (swoole_websocket_server $server, $request) {;
            $str = 'abcdefghijklmnopqrstuvwxyz';
            $userInfo = array(
                'id' => $request->fd,
                'name' => substr($str,rand(0,10),5)
            );
            $this->connecter[$request->fd] = $userInfo;
            $temp = array('info' => '当前链接人数','data' => $this->connecter);
            $server->push($request->fd,json_encode($temp)); //告诉自己当前连接人数

            foreach($this->connecter as $item){//通知其他人 有客人来了
                if($item['id'] != $userInfo['id']){//自己就不通知了
                    $server->push($item['id'], "欢迎{$userInfo['name']}加入！");
                }
            }
            echo "server: handshake success with fd{$request->fd}\n";
        });
        $this->server->on('message', function (swoole_websocket_server $server, $frame) {
            $data = json_decode($frame->data,1);
            echo "receive from {$frame->fd}; act:{$data['act']}; data:{$data['data']},opcode:{$frame->opcode},fin:{$frame->finish}\n";

            if($data['act'] == 'send_file'){//调用task
                $server->task(array('fd' => $frame->fd,'data' => 'this is file'));
            }

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