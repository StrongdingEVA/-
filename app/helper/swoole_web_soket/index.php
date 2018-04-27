<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:43
 */

error_reporting(E_ALL);
//$rootPath = __DIR__;
//require 'autoload/autoload.php';
//\autoload\Autoload::run($rootPath);
new \server\Server(array('addr' => '0.0.0.0','11223'));