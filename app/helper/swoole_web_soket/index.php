<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:43
 */
use server\WebsocketTest;
use autoload\Autoload;

error_reporting(E_ALL);
$rootPath = __DIR__;
require 'autoload/autoload.php';
Autoload::run($rootPath);
new WebsocketTest();