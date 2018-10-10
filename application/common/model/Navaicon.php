<?php

namespace app\common\model;

use think\Model;

class Navaicon extends Model {

    public $page_info;

    /**
     * 新增学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function navaicon_add($param) {
        return db('navaicon')->insertGetId($param);
    }


    /**
     * 删除一个学校类型
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function navaicon_del($cl_id) {
        $cl_id = (int) $cl_id;
        return db('navaicon')->where('cl_id', $cl_id)->delete();
    }

    /**
     * 更新学校类型记录
     *
     * @param array $param 更新内容
     * @return bool
     */
    public function navaicon_update($param) {
        $cl_id = (int) $param['cl_id'];
        return db('navaicon')->where('cl_id', $param['cl_id'])->update($param);
    }

    /**
     * 获取学校类型列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function get_navaicon_List($condition = array(), $page = '', $orderby = 'id asc') {
        if ($page) {
            $result = db('navaicon')->where($condition)->order($orderby)->paginate($page, false, ['query' => request()->param()]);

            $this->page_info = $result;
            return $result->items();
        } else {
            return db('navaicon')->where($condition)->order($orderby)->select();
        }
    }
    public function getJoinList($condition,$page=''){
        $condition_str  = $this->_condition($condition);
        $field  = empty($condition['field'])?'*':$condition['field'];;
        $join_type  = empty($condition['join_type'])?'left':$condition['join_type'];
        $where = $condition_str;
        $order  = empty($condition['order'])?'article.article_sort':$condition['order'];
        $result = db('article')->alias('article')->join('__ARTICLECLASS__ article_class','article.ac_id=article_class.ac_id',$join_type)->where($where)->order($order)->field($field)->select();
        return $result;
    }


    /**
     * 根据id查询一条记录.
     *
     * @param int $id 学校类型id
     * @return array 一维数组
     */
    public function getOneById($id) {
        return db('navaicon')->where('cl_id', $id)->find();
    }
    public function getOnePkg($condition = array()) {
        return db('navaicon')->where($condition)->find();
    }
    /**
     * api获取班级类型列表
     *
     * @param array $condition 查询条件
     * @return array 二维数组
     */
    public function get_navaicon_Lists($condition = array(), $field, $orderby = 'cl_sort asc') {
            return db('navaicon')->field($field)->where($condition)->order($orderby)->select();
    }






}

?>
