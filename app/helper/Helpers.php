<?php


class Helpers{

    public static function htmlspecdecode(&$info,$key){
        if(!$info){
            return false;
        }
        $info[$key] = htmlspecialchars_decode($info[$key]);
    }

    public function GetLang() {
        $Lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
        //使用substr()截取字符串，从 0 位开始，截取4个字符
        if (preg_match('/zh-c/i',$Lang)) {
            //preg_match()正则表达式匹配函数
            $Lang = '简体中文';
        }
        elseif (preg_match('/zh/i',$Lang)) {
            $Lang = '繁體中文';
        }
        else {
            $Lang = 'English';
        }
        return $Lang;
    }
    public function GetBrowser() {
        $Browser = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i',$Browser)) {
            $Browser = 'MSIE';
        }
        elseif (preg_match('/Firefox/i',$Browser)) {
            $Browser = 'Firefox';
        }
        elseif (preg_match('/Chrome/i',$Browser)) {
            $Browser = 'Chrome';
        }
        elseif (preg_match('/Safari/i',$Browser)) {
            $Browser = 'Safari';
        }
        elseif (preg_match('/Opera/i',$Browser)) {
            $Browser = 'Opera';
        }
        else {
            $Browser = 'Other';
        }
        return $Browser;
    }
    public function GetOS() {
        $OS = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i',$OS)) {
            $OS = 'Windows';
        }
        elseif (preg_match('/mac/i',$OS)) {
            $OS = 'MAC';
        }
        elseif (preg_match('/linux/i',$OS)) {
            $OS = 'Linux';
        }
        elseif (preg_match('/unix/i',$OS)) {
            $OS = 'Unix';
        }
        elseif (preg_match('/bsd/i',$OS)) {
            $OS = 'BSD';
        }
        else {
            $OS = 'Other';
        }
        return $OS;
    }
    public function GetIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //如果变量是非空或非零的值，则 empty()返回 FALSE。
            $IP = explode(',',$_SERVER['HTTP_CLIENT_IP']);
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $IP = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $IP = explode(',',$_SERVER['REMOTE_ADDR']);
        }
        else {
            $IP[0] = 'None';
        }
        return $IP[0];
    }
    private function GetAddIsp() {
        $IP = $this->GetIP();
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $ak = 'ead1hzlvCbQFwCtsQmWGWqz4gPkFI401';


        $AddIsp = mb_convert_encoding(file_get_contents('http://api.map.baidu.com/location/ip?ak='.$ak.'&ip='.$IP),'UTF-8','GBK');
        //mb_convert_encoding() 转换字符编码。
        dd($AddIsp);
        if (preg_match('/noresult/i',$AddIsp)) {
            $AddIsp = 'None';
        } else {
            $Sta = stripos($AddIsp,$IP) + strlen($IP) + strlen('来自');
            $Len = stripos($AddIsp,'"}')-$Sta;
            $AddIsp = substr($AddIsp,$Sta,$Len);
        }
        $AddIsp = explode(' ',$AddIsp);
        return $AddIsp;
    }

    public function baiDuMap()
    {
        $IP = $this->GetIP();
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $IP = $IP == "127.0.0.1" ? "110.86.4.236" : $IP;
        $ak = 'ead1hzlvCbQFwCtsQmWGWqz4gPkFI401';
        $AddIsp = mb_convert_encoding(file_get_contents('http://api.map.baidu.com/location/ip?ak='.$ak.'&ip='.$IP),'UTF-8','GBK');
        $AddIsp = json_decode($AddIsp,true);
        if ($AddIsp['status'] != 0) {
            $AddIsp = [
                "address" => "",
                "content" => [
                    "address_detail" => [
                        "province" => "",
                        "city" => "",
                        "district" => "",
                        "street" => "",
                        "street_number" => "",
                        "city_code" => ""
                    ],
                    "address" => "",
                    "point" => [
                        "y" => "",
                        "x" => "",
                    ]
                ],
                "status" => 0
            ];
        }

        return $AddIsp;
    }

    public function GetXY()
    {
        return $this->baiDuMap()['content']['point'];
    }

    public function GetAddress()
    {
        $map = $this->baiDuMap();

        $data['province'] = $map['content']['address_detail']['province'];
        $data['city'] = $map['content']['address_detail']['city'];
        $data['district'] = $map['content']['address_detail']['district'];
        $data['street'] = $map['content']['address_detail']['street'];
        $data['street_number'] = $map['content']['address_detail']['street_number'];
        $data['address'] = $map['address'];
        return $data;
    }

    public function GetAdd() {
        $Add = $this->GetAddIsp();
        return $Add[0];
    }
    public function GetIsp() {
        $Isp = $this->GetAddIsp();
        if ($Isp[0] != 'None' && isset($Isp[1])) {
            $Isp = $Isp[1];
        }
        else {
            $Isp = 'None';
        }
        return $Isp;
    }

    public static function echoJsonAjax($status,$message = '',$ext = "",$type = 0){ //$type 0 字符串  1 数组
        $arrOut = array("status" => $status,"message" => $message,"ext"=> is_array($type) ? json_encode($ext) : $ext);
        echo json_encode($arrOut);exit();
    }

    /**
     * @param $img
     * @return array
     * 获取图片信息
     */
    public static function getExif($img){
        $exif = exif_read_data($img, 'IFD0');
        if(empty($exif)){return false;}
        $longitude = self::getGps(isset($exif["GPSLongitude"]) ? $exif["GPSLongitude"] : false);//经度
        $latitude = self::getGps(isset($exif["GPSLatitude"]) ? $exif["GPSLatitude"] : false);//维度
        $positionInfo = self::getPosition($longitude,$latitude);
        $addr = self::getPositionInfo($positionInfo);
        $arrTemp = array (
            'make' => isset($exif['Make']) ? $exif["Make"] : "未知品牌",//器材品牌
            'model' => isset($exif['Model']) ? $exif["Model"] : "未知器材",//器材
            'fNumber' => isset($exif['FNumber']) ? $exif['FNumber'] : "未知",//光圈
            'focalLength' => isset($exif['FocalLength']) ? $exif['FocalLength'] : "未知",//焦距
            'longitude' => $longitude,//经度
            'latitude' => $latitude,//维度
            'addr' => $addr ? $addr : "未开启位置信息",
        );
        return $arrTemp;
    }

    public static function getGps($exifCoord){
        if(!$exifCoord){return false;}
        $degrees= count($exifCoord) > 0 ? self::gps2Num($exifCoord[0]) : 0;
        $minutes= count($exifCoord) > 1 ? self::gps2Num($exifCoord[1]) : 0;
        $seconds= count($exifCoord) > 2 ? self::gps2Num($exifCoord[2]) : 0;
            //normalize
        $minutes+= 60 * ($degrees- floor($degrees));
        $degrees= floor($degrees);
        $seconds+= 60 * ($minutes- floor($minutes));
        $minutes= floor($minutes);
            //extra normalization, probably not necessary unless you get weird data
        if($seconds>= 60){
            $minutes+= floor($seconds/60.0);
            $seconds-= 60*floor($seconds/60.0);
        }
        if($minutes>= 60){
            $degrees+= floor($minutes/60.0);
            $minutes-= 60*floor($minutes/60.0);
        }
        $seconds = intval($seconds * 10000);
        return $degrees.".".$minutes.$seconds;
    }


    public static function gps2Num($coordPart){
    $parts= explode('/', $coordPart);
        if(count($parts) <= 0)
            return 0;
        if(count($parts) == 1)
            return$parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

    /**
     * 获取地址
     */
    public static function getPosition($lon,$lat){
        if(!$lon || !$lat){
            return json_encode(array());
        }
        $url = "http://api.map.baidu.com/geocoder/v2/?callback=renderReverse&location={$lat},{$lon}&output=json&pois=1&ak=CqctFPnWwKFLV61ZUKGwAg1v"; //每天6000次
        $curl = curl_init($url);
//        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_TIMEOUT,10);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
//        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: text/plain'));
        $result = curl_exec($curl);
        if($result){
            $result_1 = substr($result,strpos($result,"("));
        }
        $result_1 = trim($result_1,"()");
        curl_close($curl);
        return $result_1;
    }

    /**
     * 获取所需的信息
     */
    public static function getPositionInfo($addreInfo,$key = "formatted_address"){
        $addreInfo = json_decode($addreInfo,true);
        if(!is_array($addreInfo) || empty($addreInfo)){
            return false;
        }
        if($addreInfo["status"] != 0){
            return false;
        }
        $result = $addreInfo["result"];
        return $result[$key];
    }

    /**
     * 给文章ID加密
     */
    public static function encrytById(&$item,$key = "id",$type = 0){
//        if($type){
//            $item[$key] = \Illuminate\Support\Facades\Crypt::encrypt((int)$item[$key]);
//        }else{
//            $item->$key = \Illuminate\Support\Facades\Crypt::encrypt((int)$item->$key);
//            return $item->$key;
//        }
    }

    /**
     * 给文章ID解密
     */
    public static function encrytDeById(&$articleId){
//        $articleId = \Illuminate\Support\Facades\Crypt::decrypt($articleId);//解密
    }

    public static function clearStr($str){
        $temp = htmlspecialchars($str);
        $temp = preg_replace('/\&lt;p\&gt;|\&lt;\/p\&gt;|\&lt;br\/\&gt;/U','',$temp);
        return $temp;
    }

    public static function getThumbFromVideo($path){
        $fix = "D:/WWW/laravel-v5.1.11/public";
        $filePath = "/upload/articleimg/thumb/".date("Ymd",time());
        is_dir($filePath) ? mkdir($filePath) : "";
        $fileName = "/".date("YmdHis",time()).mt_rand(100,999).".jpg";
        $ffmpegUrl = "$fix/ffmpeg/bin/ffmpeg";
        $videoUrl = $fix . $path;
        $fileName = $fix . $filePath . $fileName;
        exec("$ffmpegUrl -i $videoUrl -r 1 -ss 0:0:1 -t 0:0:1 -f image2 $fileName",$arrOut);
        return $fileName;
    }

    /**
     * 上传文件
     * @type = 1 小文件  2 大文件
     * @param Request $request
     */
    public static function uploadimg($alowArr,$dir,$type = 1){
        if(empty($alowArr)){
            $alowArr = array("jpg","jpeg","png","gif");
        }
        $type == 1 ? $size = 5000 : $size = 200000;
        $uploadClass = new \Upload($size,$alowArr);
        $uploadClass->setDir($dir);
        $result = $uploadClass->execute();
        $result = $result["Filedata"][0];
        if($result["flag"] == 1){
            return array(
                "result" => $result["fileSrc"],
                "status" => 0,
            );
        }
        return array(
            "status" => -1,
            "message" => $uploadClass->errorMessage($result["flag"])
        );
    }


    /*
    * 修改图片尺寸
    */
    public static function resizejpg($imgsrc,$imgdst,$imgwidth,$imgheight){
        if(!$imgwidth && !$imgheight){
            return false;
        }

        $imgType = exif_imagetype($imgsrc); //返回数字表示图片格式
        switch($imgType){
            case 1:
                $hand = imagecreatefromgif($imgsrc); //gif
                break;
            case 2:
                $hand = imagecreatefromjpeg($imgsrc); //jpeg
                break;
            case 3:
                $hand = imagecreatefrompng($imgsrc); //png
                break;
            default:
                $hand = "";
        }
        //$imgsrc jpg格式图像路径 $imgdst jpg格式图像保存文件名 $imgwidth要改变的宽度 $imgheight要改变的高度
        //取得图片的宽度,高度值
        $arr = getimagesize($imgsrc);
        //header("Content-type: image/jpg");

        if(!$hand){
            return 1;
        }

        $dir = substr($imgdst,2,strrpos($imgdst,'/')-2);
        if(!is_dir($dir)){
            mkdir($dir);
        }

        $oldWidth = imagesx($hand);
        $oldHiehgt = imagesy($hand);

        if($imgwidth && $imgheight){
            $imgWidth = $imgwidth;
            $imgHeight = $imgheight;
        }else if($imgwidth){
            $p = $imgwidth / $oldWidth;
            $imgWidth = $imgwidth;
            $imgHeight = (int)($p * $oldHiehgt);
        }else if($imgheight){
            $p = $imgheight / $oldHiehgt;
            $imgWidth = (int)($oldWidth * $p);
            $imgHeight = $imgheight;
        }

        // Create image and define colors
        $image = imagecreatetruecolor($imgWidth, $imgHeight);  //创建一个彩色的底图
        imagecopyresampled($image, $hand, 0, 0, 0, 0,$imgWidth,$imgHeight,$arr[0], $arr[1]);
        if(imagepng($image,$imgdst)){
            imagedestroy($image);
            return true;
        }
        imagedestroy($image);
        return false;
    }

    /**
     * 遍历目录的图片
     */
    public static function getFilePics($path){
        set_time_limit(0);
        if($path){
            return false;
        }
        $dir = $_SERVER["DOCUMENT_ROOT"].$path;
        $handle = opendir($dir);
        while (false !== ($file = readdir($handle))) { //遍历该php文件所在目录
            list($filesname,$kzm)=explode(".",$file);//获取扩展名
            if($kzm=="gif" or $kzm=="jpg" or $kzm=="JPG") { //文件过滤
                if (!is_dir('./'.$file)) { //文件夹过滤
                    $array[]=$file;//把符合条件的文件名存入数组
                }
            }
        }
        return $array;
    }
}