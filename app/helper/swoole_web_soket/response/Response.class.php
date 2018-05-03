<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3 0003
 * Time: 15:47
 */
namespace response;
class Response{
    public static function resMsg($msg = '',$code = -1,$data = ''){
        return array('code' => $code,'msg' => $msg,'data' => $data);
    }
}