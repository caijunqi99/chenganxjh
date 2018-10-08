<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 10:17
 */
namespace app\common\model;

use think\Model;

class Teachchlid extends Model {
    /**
     * 分子公司列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getTeachchildList($condition, $field = '*', $page = 0, $order = 'o_id desc', $limit = '') {
        if($limit) {
            return db('teachchild')->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
        }else{
            $res= db('teachchild')->where($condition)->field($field)->order($order)->paginate($page,false,['query' => request()->param()]);
            $this->page_info=$res;
            return $res->items();
        }
    }
    /**
     * 添加分子公司
     * @param array $insert
     * @return boolean
     */
    public function addTeachchild($insert) {
        return db('teachchild')->insert($insert);
    }
    /**
     * 取单个分子公司内容
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTeachchildInfo($condition, $field = '*') {
        return db('teachchild')->field($field)->where($condition)->find();
    }
    /**
     * 编辑分子公司
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editTeachchild($condition, $update) {
        return db('teachchild')->where($condition)->update($update);
    }

}