<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3 0003
 * Time: 15:17
 */
namespace manage;
use response\Response;
class Room{
    public $room_name = ''; //房间名称
    public $room_id = ''; //房间唯一ID
    public $room_belong = ''; //房间拥有者   存放user_id
    public $room_connecter = []; //房间内的用户连接

    public function __construct($options = []){
        $this->room_name = $options['room_name'] ? $options['room_name'] : '默认房间名';
        $this->room_id = $this->getRid();
        $this->room_belong = $options['room_belong'];
        $this->addLink($options['uinfo']);
    }

    /**
     * 获取唯一的房间ID
     */
    public function getRid( $length = 10 ){
        // 密码字符集，可任意添加你需要的字符
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars,$length);
        $key = '';
        for($i = 0; $i < $length; $i++){
            $key .= $chars[$keys[$i]];
        }
        return $key;
    }

    /**
     * 从房间里踢一个人
     * @param $user_id  要被踢的那个人
     * @param $whoDoThis 房主
     * @return array
     */
    public function delLink($user_id,$whoDoThis){
        //只有房间主人可以踢人
        if(!$user_id || !$whoDoThis){
            return Response::resMsg('操作缺少参数');
        }
        if($this->room_belong != $whoDoThis){
            return Response::resMsg('房间主人才有权限让人离开');
        }
        $suffer = $this->room_connecter[$user_id];
        if(!$suffer){
            return Response::resMsg('不存在该用户');
        }
        $suffer->server->close($suffer['fd']);
        unset($this->room_connecter[$user_id]);
        return Response::resMsg('成功',0);
    }

    /**
     * 添加一个用户到房间
     * @param array $param   array('user_id' => 1,'username' =>'testname','logo' => '.....','gental' => 1,'server' => server,'fd' => 1)
     */
    public function addLink($param = array()){
        if(!$param['user_id'] || !$param['username']){
            return Response::resMsg('非法用户');
        }
        //判断用户是否已经在房间内
        if(isset($this->room_connecter[$param['user_id']])){
            return Response::resMsg('该用户已经在此房间了');
        }

        $this->room_connecter[][$param['user_id']] = $param;
        return Response::resMsg('成功',0);
    }

    /**
     * 设置房间所属
     * @param $user_id
     * @return bool
     */
    protected function setBelong($user_id){
        if(!$user_id){
            return Response::resMsg('所属者不能为空');
        }
        $this->room_belong = $user_id;
        return Response::resMsg('房间有新主人了');
    }
}