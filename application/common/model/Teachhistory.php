<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 10:17
 */
namespace app\common\model;

use think\Model;

class Teachhistory extends Model {
    /**
     * 课件观看历史列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getTeachhistoryList($condition, $field = '*', $page = 0, $order = 't_id asc', $limit = '') {
        if($limit) {
            return db('teachhistory')->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
        }else{
            $res= db('teachhistory')->where($condition)->field($field)->order($order)->paginate($page,false,['query' => request()->param()]);
            $this->page_info=$res;
            return $res->items();
        }
    }
    /**
     * 添加课件观看历史
     * @param array $insert
     * @return boolean
     */
    public function addTeachhistory($insert) {
        return db('teachhistory')->insert($insert);
    }
    /**
     * 取单个课件观看历史
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTeachhistoryInfo($condition, $field = '*') {
        return db('teachhistory')->field($field)->where($condition)->find();
    }
    /**
     * 编辑课件观看历史
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editTeachhistory($condition, $update) {
        return db('teachhistory')->where($condition)->update($update);
    }

}