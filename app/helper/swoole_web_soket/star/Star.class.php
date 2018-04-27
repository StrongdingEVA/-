<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 11:03
 */
namespace star;
use server;
class Star{
    public $options = array();
    public function __construct($options = array()){

    }

    public static function run(){
        $options = array(
            'addr' => '0.0.0.0',
            'port' => 11223
        );
        $server = new server\Server($options);
        $server->run();
    }
}