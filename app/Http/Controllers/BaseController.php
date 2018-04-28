<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/28 0028
 * Time: 9:43
 */
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller{
    public function __construct(){
        $userInfo = Auth::user()->toArray();
        print_r($userInfo);
    }
}