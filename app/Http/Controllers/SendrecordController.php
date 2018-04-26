<?php

namespace App\Http\Controllers;

use App\Sendrecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SendrecordController extends Controller
{
    /**
     * @param $recordId
     * @return bool
     * 获取验证码
     */
    public static function getRecord($recordId){
        if(empty($recordId)){
            return false;
        }
        $checkInfo = DB::table("sendrecords")->where("id",$recordId)->first();
        if($checkInfo){
            return $checkInfo;
        }
        return false;
    }

    /**
     * @更新状态为已验证
     * @param $recordId
     * @return mixed
     */
    public static function setStatus($recordId){
        return DB::table("sendrecords")->where('id', $recordId)->update(['is_check' => 1]);
    }

    /**
     * 插入新的验证码
     * @param $email
     * @param $number
     * @return bool
     */
    public static function insertRecord($email,$number){
        $sendModel = new Sendrecord();
        $sendModel->email = $email;
        $sendModel->number = $number;
        $sendModel->created_at = date("Y-m-d H:i:s");
        $sendModel->save();
        return $sendModel->id;
    }
}
