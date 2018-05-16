<?php

namespace App\Http\Controllers;

use App\Hotpic;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Helper\Helper;

session_start();
class PictureWallController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$type)
    {
        $actionLi = 5;
        $pageSize = 16;
        $hotPicItem = Hotpic::getHotPic(1,$type,$pageSize);
        $hotPicItem = self::isLiked($hotPicItem);
        $total = Hotpic::getTotal();
        $pageCount = ceil($total / $pageSize);
        $active = 'picwall';
        return view("Home.picturewall",compact("actionLi","hotPicItem","pageCount",'active','type'));
    }

    public function getCreate(Request $request){
        $active = 'picwall';
        return view("Home.addpicturewall",compact("active"));
    }

    public function postCreate(Request $request){
        $picWallInfo = $request->all();
        $validator = $this->validator($picWallInfo);
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $par = isset($_SESSION['picWall']) ? $_SESSION["picWall"] : false;
        if(!$par){
            Redirect::back()->withInput($picWallInfo);
        }
        $picWallInfo["user_id"] = Auth::user()->id;
        $hotPic = new Hotpic();
        $hotPic->disc = $picWallInfo["disc"];
        $hotPic->user_id = $picWallInfo["user_id"];
        $hotPic->path = str_replace("thumb","source",$picWallInfo["thumb"]);
        $hotPic->thumb = $picWallInfo["thumb"];
        $hotPic->tehme = 0;
        $hotPic->model = $par["model"];//器材
        $hotPic->make = $par["make"]; //器材品牌
        $hotPic->focallength = $par["focalLength"];//焦距
        $hotPic->longitude = $par["longitude"];//经度
        $hotPic->latitude = $par["latitude"];//维度
        $hotPic->fnumber = $par["fNumber"];//光圈
        $hotPic->addr = $par["addr"];
        $hotPic->isshowparm = isset($picWallInfo["isshowparm"]) ? 1 : 0;
        $res_1 = $hotPic->save($picWallInfo);
        if(!$res_1){
            return Redirect::back()->withInput($hotPic);
        }
        return redirect('/picturewall/old');
    }

    /**
     * 上传图片
     * @param Request $request
     */
    public function uploadimgWall(Request $request){
        $alowArr = array("jpg","jpeg","png","gif");
        $dir = "upload/pciwall";
        $result = \Helpers::uploadimg($alowArr,$dir);
        if($result["status"]==0){
            $exif = \Helpers::getExif($result["result"]);//\Helpers::echoJsonAjax(-1,$exif);
            $_SESSION["picWall"] = $exif;
            if(!$exif){
                \Helpers::echoJsonAjax(-1,"必须是使用器材拍摄的照片哦~!");
            }

            $imgDst = str_replace("source","thumb",$result["result"]);
            $thumbDir = substr($imgDst,0,strrpos($imgDst,'/'));
            if(!is_dir($thumbDir)){
                @mkdir($thumbDir);
            }
            $Image = new \img\Image();
            $Image->open($result["result"]);
            $Image->thumb(900, 900)->save($result["result"]);
            $Image->thumb(300, 300)->save($imgDst);
            $exif["src"] = "/".$result["result"];
            \Helpers::echoJsonAjax(1,$request->session()->all(),$exif,1);

//            $imgDst = str_replace("source","thumb",$result["result"]);
//            if(\Helpers::resizejpg($result["result"],"./".$imgDst,0,200)){
//                $exif["src"] = "/".$imgDst;
//                \Helpers::echoJsonAjax(1,$request->session()->all(),$exif,1);
//            }
//            \Helpers::echoJsonAjax(-1,"压缩失败");
        }else{
            $this->arrOut["status"] = -1;
            $this->arrOut["message"] = $result["message"];
        }
        \Helpers::echoJsonAjax(-1,$this->arrOut);
    }

    /**
     * 点赞
     */
    public function doLikeAction(Request $request,$picId,$type){
        //调用MODEL方法
        if(empty($picId)){
            \Helpers::echoJsonAjax(-1,"参数错误");
        }
        $user = @Auth::user();
        if(!$user){
            \Helpers::echoJsonAjax(-1,"请先登录");
        }
        $result =  $type == 0 ? $this->doLike($picId) : $this->doCancleLike($picId);
        $result ? \Helpers::echoJsonAjax(1,self::getLikeCount($picId)) : \Helpers::echoJsonAjax(-1,"点赞失败");
    }

    protected function validator(array $data)
    {
        return \Illuminate\Support\Facades\Validator::make($data, [
            'thumb' => 'required',
            'disc' => 'required',
        ]);
    }

    /**
     * 点赞
     */
    public function doLike($picId){
        $user = @Auth::user();
        if(!$user){
            \Helpers::echoJsonAjax(-1,"请登录后操作");
        }

        //判断是否点赞过
        $pInfo = Hotpic::where("id","$picId")->first();
        $likerInfo = $pInfo->liker ? json_decode($pInfo->liker,1) : array();
        if(in_array($user->id,$likerInfo)){
            \Helpers::echoJsonAjax(-1,"已经点赞过了哦！");
        }
        DB::begintransaction();
        $res_1 = self::addAndSubLike($picId);
        $res_2 = self::addAndSubLiker($picId);
        if(!$res_1 || !$res_2){
            DB::rollBack();
            return false;
        }else{
            DB::commit();
            return true;
        }
    }

    public function doCancleLike($picId){
        $user = @Auth::user();
        if(!$user){
            \Helpers::echoJsonAjax(-1,"请登录后操作");
        }

        //判断是否点赞过
        $pInfo = Hotpic::where("id","$picId")->first();
        $likerInfo = $pInfo->liker ? json_decode($pInfo->liker,1) : array();
        if(!in_array($user->id,$likerInfo)){
            \Helpers::echoJsonAjax(-1,"未点赞无法取消！");
        }
        DB::begintransaction();
        $res_1 = self::addAndSubLike($picId,2);
        $res_2 = self::addAndSubLiker($picId,2);
        if(!$res_1 || !$res_2){
            DB::rollBack();
            return false;
        }else{
            DB::commit();
            return true;
        }
    }

    /**
     * 增加或减少点赞次数
     * @param $picId
     * @param $type = 1 增加  否则减少
     */
    public static function addAndSubLike($picId,$type = 1){
        $pInfo = Hotpic::where("id","$picId")->first();
        $likeInfo = $pInfo->like;
        $likeInfo = $type == 1 ? $likeInfo + 1 : $likeInfo - 1;
        return Hotpic::where("id","$picId")->update(["like"=>$likeInfo]);
    }

    /**
     * 增加或者减少点赞者
     * @param $picId
     * @param int $type = 1 增加  否则减少
     */
    public static function addAndSubLiker($picId,$type = 1){
        $pInfo = Hotpic::where("id","$picId")->first();
        $likerInfo = $pInfo->liker ? json_decode($pInfo->liker,1) : array();
        if($type == 1) {
            $likerInfo[] = Auth::user()->id;
        }else {
            unset($likerInfo[array_search(Auth::user()->id,$likerInfo)]);
        }
        return Hotpic::where("id","$picId")->update(["liker"=> json_encode($likerInfo)]);
    }

    /**
     * @param $picId
     * @return mixed
     * 返回点赞次数
     */
    public static function getLikeCount($picId){
        $pInfo = Hotpic::where("id","$picId")->first();
        return $pInfo->like;
    }

    /**
     * @param $hotPics
     * @return bool
     * 判断用户是否点赞了图片
     */
    public static function isLiked($hotPics){
        if(!is_array($hotPics) && empty($hotPics)){
            return false;
        }

        if(Auth::check()){
            $uid = Auth::user()->id;
        }else{
            $uid = "notlogin";
        }

        foreach($hotPics as $key => $val) {
            $liker = $val["liker"] ? json_decode($val["liker"], 1) : array();
            if (in_array($uid, $liker)) {
                $hotPics[$key]["liked"] = 1;
            }else{
                $hotPics[$key]["liked"] = 0;
            }
        }
        return $hotPics;
    }

    public function picAjax(Request $request,$page,$order){
        $hotPicItem = Hotpic::getHotPic($page,$order);
        $hotPicItem = self::isLiked($hotPicItem);
        \Helpers::echoJsonAjax(1,"获取数据成功",$hotPicItem,1);
    }
}
