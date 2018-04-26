<?php

namespace App;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Hotpic extends Model
{
    //
    protected $fillable = ['disc', 'path', 'like','liker','tehme','model','make','focallength','longitude','latitude','fnumber','addr','isshowparm','iswinner'];

    public function getUser(){
        return $this->belongsTo('App\User',"user_id","id");
    }

    /**
     * @return mixed
     * 获取本期热图
     */
    public static function getHotPic($page = 1,$type,$perPage = 16,$adtion = ""){
        if ($page) {
            $current_page = $page;
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }

        switch ($type){
            case "old":
                $colum = "id";
                $str = "asc";
                break;
            case "new":
                $colum = "id";
                $str = "desc";
                break;
            case "hot";
                $colum = "like";
                $str = "desc";
                break;
            default:
                $colum = "id";
                $str = "desc";
                break;
        }

        $picList = Hotpic::with("getUser")->orderBy("{$colum}","{$str}")->get()->toArray();

        $item = array_slice($picList, ($current_page-1) * $perPage, $perPage); //注释1
        $total = count($picList);
        $currentPage = "";
        $paginator = new LengthAwarePaginator($item, $total, $perPage, $currentPage, []);
        return $paginator->toArray()["data"];
    }

    public static function getTotal($adtion = ""){
        return count(Hotpic::get());
    }
}
