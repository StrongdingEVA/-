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
    public $uId = '';
    public $uInfo = '';

    public function __construct(){
        $userInfo = Auth::user();
        $temp = array();
        if($userInfo){
            $temp['id'] = $userInfo->id;
            $temp['username'] = $userInfo->username;
            $temp['email'] = $userInfo->email;
            $temp['mobile'] = $userInfo->mobile;
            $temp['user_point'] = $userInfo->user_point;
            $temp['level_point'] = $userInfo->level_point;
            $temp['post_count'] = $userInfo->post_count;
            $temp['logo'] = $userInfo->logo;
            $temp['user_status'] = $userInfo->user_status;
            $temp['user_status'] = $userInfo->user_status;
            $this->uInfo = $temp;
            $this->uId = $temp['id'];
        }
        view()->share('userInfo',$temp);
    }
}