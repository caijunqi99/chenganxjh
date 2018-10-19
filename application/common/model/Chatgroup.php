<?php

namespace app\common\model;

use think\Model;

class Chatgroup extends Model {

    public $page_info;

    /**
     * 新增学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function chatgroup_add($param) {
        return db('chatgroup')->insertGetId($param);
    }


    public function chatgroup_addAll($param) {
        return db('chatgroupmember')->insertAll($param);
    }


    public function chatgroup_set($key,$velue){
        return db('chatgroup')->where('group_id', $group_id)->setField($key, $velue);
    }
    /**
     * 删除一个学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function chatgroup_del($group_id) {
        $group_id = (int) $group_id;
        return db('chatgroup')->where('group_id', $group_id)->delete();
    }

    /**
     * 更新学校类型记录
     *
     * @param array $param 更新内容
     * @return bool
     */
    public function chatgroup_update($param) {
        $group_id = (int) $param['group_id'];
        return db('chatgroup')->where('group_id', $param['group_id'])->update($param);
    }

    /**
     * 获取学校类型列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function get_chatgroup_List($condition = array(), $page = '', $orderby = 'group_id asc') {
        if ($page) {
            $result = db('chatgroup')->where($condition)->order($orderby)->paginate($page, false, ['query' => request()->param()]);
            $this->page_info = $result;
            return $result->items();
        } else {
            return db('chatgroup')->where($condition)->order($orderby)->select();
        }
    }


    /**
     * 根据id查询一条记录
     *
     * @param int $id 学校类型id
     * @return array 一维数组
     */
    public function getOneById($id) {
        return db('chatgroup')->where('group_id', $id)->find();
    }
    public function getOnePkg($condition = array()) {
        return db('chatgroup')->where($condition)->find();
    }
    /**
     * api获取学校类型列表
     *
     * @param array $condition 查询条件
     * @return array 二维数组
     */
    public function get_chatgroup_Lists($condition = array(),$field, $orderby = 'group_id asc') {
            return db('chatgroup')->field($field)->where($condition)->order($orderby)->select();
    }






}

?>
