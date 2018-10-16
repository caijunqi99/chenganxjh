<?php

namespace app\common\model;

use think\Model;

class Schoolfood extends Model {

    public $page_info;

    /**
     * 新增学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function schoolfood_add($param) {
        return db('schoolfood')->insertGetId($param);
    }


    /**
     * 删除一个学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function schoolfood_del($bus_id) {
        $bus_id = (int) $bus_id;
        return db('schoolfood')->where('bus_id', $bus_id)->delete();
    }

    /**
     * 更新学校类型记录
     *
     * @param array $param 更新内容
     * @return bool
     */
    public function schoolfood_update($param,$condition='') {
        if ($condition) {
            return db('schoolfood')->where($condition)->update($param);
        }else{
            $bus_id = (int) $param['bus_id'];  
            return db('schoolfood')->where('bus_id', $param['bus_id'])->update($param);  
        }
        
        
    }

    /**
     * 获取学校类型列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function get_schoolfood_List($condition = array(), $page = '', $orderby = 'bus_id asc') {
        if ($page) {
            $result = db('schoolfood')->where($condition)->order($orderby)->paginate($page, false, ['query' => request()->param()]);
            $this->page_info = $result;
            return $result->items();
        } else {
            return db('schoolfood')->where($condition)->order($orderby)->select();
        }
    }


    /**
     * 根据id查询一条记录
     *
     * @param int $id 学校类型id
     * @return array 一维数组
     */
    public function getOneById($id) {
        return db('schoolfood')->where('bus_id', $id)->find();
    }
    public function getOnePkg($condition = array()) {
        return db('schoolfood')->where($condition)->find();
    }
    /**
     * api获取学校类型列表
     *
     * @param array $condition 查询条件
     * @return array 二维数组
     */
    public function get_schoolfood_Lists($condition = array(),$field, $orderby = 'bus_id asc') {
            return db('schoolfood')->field($field)->where($condition)->order($orderby)->select();
    }






}

?>
