<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:43
 */
use autoload\Autoload;
use manage\Msg;

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
            //长时任务交给task处理 data 里可以存放fd等数据 在此处通知客户端
        });

        $this->server->on('finish',function($server,$task_id, $data){
            //任务处理完成 回调这里 data 里可以存放fd等数据 在此处通知客户端
        });

        //握手成功回调
        $this->server->on('open', function (swoole_websocket_server $server, $request) {;
            //随机一个用户名
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

        //收到消息回调
        $this->server->on('message', function (swoole_websocket_server $server, $frame) {
            //规定客户端发送json字符串
            $data = json_decode($frame->data,1);  //接收客户端发来的消息
            $act = $data['act'];
            $param['data'] = $data['data'];
            $param['fd'] = $frame->fd;

            $route = $this->getRoute();
            if(!isset($route[$act]) || empty($route[$act])){
                $server->push($frame->fd, "操作规则不存在");
                return;
            }
            $realyAct = $route[$act];
            $path = 'manage/' . $realyAct . '.class.php';
            if(!is_file($path)){
                $server->push($frame->fd, "操作类{$realyAct}不存在");
                return;
            }
            require_once 'manage/Msg.class.php';
            $manage = new \manage\Msg();
//            $manage = new $realyAct();
            $manage->run($this,$param);
//            if($data['act'] == 'send_file'){//调用task
//                $server->task(array('fd' => $frame->fd,'data' => 'this is file'));
//            }
        });

        $this->server->on('close', function ($server, $fd) { // 客户端关闭

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

    public function getRoute(){
        return array(
            'send_t_u' => 'Msg',
        );
    }
}

new WebsocketTest();