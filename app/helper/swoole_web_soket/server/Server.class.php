<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:52
 */
namespace server;
class Server {
    public $server;
    public $addr = '0.0.0.0';
    public $port = '11223';
    public function __construct($options = array()) {
        $options['addr'] && $this->addr = $options['addr'];
        $options['port'] && $this->port = $options['port'];
    }

    public function run(){
        $server = new swoole_websocket_server($this->addr, $this->port);
        $server->on('open', function (swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });
        $server->on('message', function (swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });
        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });
        $server->on('request', function ($request, $response) {
            global $server;//调用外部的server
            // 接收http请求从get获取message参数的值，给用户推送
            // $server->connections 遍历所有websocket连接用户的fd，给所有用户推送
            foreach ($server->connections as $fd) {
                $server->push($fd, $request->get['message']);
            }
        });

        $server->start();
    }
}