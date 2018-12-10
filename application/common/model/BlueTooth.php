<?php

namespace app\common\model;

use think\Model;

class BlueTooth extends Model {
    public $page_info;

    public function getBlueToothCount($condition){
        /*$str = db('blueTooth')->where($condition)->group('appid')->having($this->count('appid')>1);
        $condition['appid'] = array('in',$str);*/
        return db('blueTooth')->where($condition)->count();
    }

    public function isset_blueTooth($condition){
        $result = db('blueTooth')->where($condition)->find();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function blueTooth_add($param) {
        return db('blueTooth')->insertGetId($param);
    }

}