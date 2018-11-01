<?php

namespace app\common\model;

use think\Model;

class Camera extends Model {

    public $page_info;

    /**
     * 新增学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function camera_add($param) {
        return db('camera')->insertGetId($param);
    }
    /**
     * 批量添加摄像头
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function cameras_add($param) {
        return db('camera')->insertAll($param);
    }


    /**
     * 删除一个学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function camera_del($sc_id) {
        $sc_id = (int) $sc_id;
        return db('camera')->where('sc_id', $sc_id)->delete();
    }

    /**
     * 更新学校类型记录
     *
     * @param array $param 更新内容
     * @return bool
     */
    public function camera_update($param) {
        $sc_id = (int) $param['sc_id'];
        return db('camera')->where('sc_id', $param['sc_id'])->update($param);
    }

    /**
     * 获取学校类型列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function getCameraList($condition, $page = '', $field = '*', $school = 'cid desc', $limit = '', $extend = array(), $master = false) {
        $list_paginate = db('camera')->field($field)->where($condition)->order($school)->paginate($page,false,['query' => request()->param()]);
        $this->page_info = $list_paginate;
        $list = $list_paginate->items();

        if (empty($list))
            return array();
        return $list;
    }


    /**
     * 根据id查询一条记录
     *
     * @param int $id 学校类型id
     * @return array 一维数组
     */
    public function getOneById($id) {
        return db('camera')->where('sc_id', $id)->find();
    }
    public function getOnePkg($condition = array()) {
        return db('camera')->where($condition)->find();
    }

}

?>
