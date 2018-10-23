<?php

namespace app\common\model;
use think\Model;

class Classes extends Model {
    public $page_info;
    
    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @return unknown
     */
    public function getClassInfo($condition = array(), $extend = array(), $fields = '*', $class = '', $group = '') {
        $class_info = db('class')->field($fields)->where($condition)->group($group)->order($class)->find();
        if (empty($class_info)) {
            return array();
        }
        return $class_info;
    }

    /**
     * 取得学校列表(所有)
     * @param unknown $condition
     * @param string $page
     * @param string $field
     * @param string $school
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getClasslList($condition, $page = '', $field = '*', $class = 'classid desc', $limit = '', $extend = array(), $master = false) {
        //$list_paginate = db('class')->alias('s')->join('__ADMIN__ a',' a.admin_id=s.option_id ','LEFT')->field($field)->where($condition)->order($class)->paginate($page,false,['query' => request()->param()]);
        $list_paginate = db('class')->field($field)->where($condition)->order($class)->paginate($page,false,['query' => request()->param()]);
        //$sql =  db('school')->getlastsql();
        if($condition){
            //print_r($sql);die;
        }

        $this->page_info = $list_paginate;
        $list = $list_paginate->items();

        if (empty($list))
            return array();
        return $list;
    }

    public function getAllClasses($condtion){
        //$result = db('class')->alias('s')->join('__ADMIN__ a',' a.admin_id=s.option_id ','LEFT')->where($condtion)->select();
        $result = db('class')->where($condtion)->select();
        return $result;
    }

    /**
     * 插入班级表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addClasses($data) {
        $insert = db('class')->insertGetId($data);
        return $insert;
    }

    /**
     * 添加订单日志
     */
    public function addOrderLog($data) {
        $data['log_role'] = str_replace(array('buyer', 'seller', 'system', 'admin'), array('买家', '商家', '系统', '管理员'), $data['log_role']);
        $data['log_time'] = TIMESTAMP;
        return db('orderlog')->insertGetId($data);
    }

    /**
     * 更改班级信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editClass($data, $condition, $limit = '') {

        $update = db('class')->where($condition)->limit($limit)->update($data);
        return $update;
    }



    /**
     * @name 获取某地区编码数
     * @param $str
     * @return null|string
     */
    public function getNumber($str){
        $where = "classCard like '%$str%'";
        $classInfo = db('class')->where($where)->order('classid desc')->limit(1)->find();
        $number = sprintf("%04d", substr($classInfo['classCard'],-4)+1);
        return $number;
    }

}