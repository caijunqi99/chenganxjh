<?php

namespace app\common\model;
use think\Model;

class Student extends Model {
    public $page_info;
    
    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @return unknown
     */
    public function getStudentInfo($condition = array(), $extend = array(), $fields = '*', $class = '', $group = '') {
        $class_info = db('student')->field($fields)->where($condition)->group($group)->order($class)->find();
        if (empty($class_info)) {
            return array();
        }
        return $class_info;
    }

    public function getOrderCommonInfo($condition = array(), $field = '*') {
        return db('ordercommon')->where($condition)->find();
    }

    public function getOrderPayInfo($condition = array(), $master = false) {
        return db('orderpay')->where($condition)->master($master)->find();
    }

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $page
     * @param unknown_type $filed
     * @param unknown_type $order
     * @param string $key 以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getOrderPayList($condition, $page = '', $filed = '*', $order = '', $key = '') {
        $pay_list = db('orderpay')->field($filed)->where($condition)->order($order)->page($page)->select();
        if($key){
            $pay_list = ds_changeArraykey($pay_list, $key);
        }
        
        return $pay_list;
    }

    /**
     * 取得订单列表(未被删除)
     * @param unknown $condition
     * @param string $page
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getNormalOrderList($condition, $page = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()) {
        $condition['delete_state'] = 0;
        return $this->getOrderList($condition, $page, $field, $order, $limit, $extend);
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
    public function getStudentList($condition, $page = '', $field = '*', $class = 's_id desc', $limit = '', $extend = array(), $master = false) {
        $list_paginate = db('student')->alias('s')->join('__ADMIN__ a',' a.admin_id=s.option_id ','LEFT')->field($field)->where($condition)->order($class)->paginate($page,false,['query' => request()->param()]);
        $this->page_info = $list_paginate;
        $list = $list_paginate->items();

        if (empty($list))
            return array();
        return $list;
    }


    /**
     * 插入班级表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addStudent($data) {
        $insert = db('student')->insertGetId($data);
        return $insert;
    }


    /**
     * 更改班级信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editStudent($data, $condition, $limit = '') {

        $update = db('student')->where($condition)->limit($limit)->update($data);
        return $update;
    }

}