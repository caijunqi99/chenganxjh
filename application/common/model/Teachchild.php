<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 10:17
 */
namespace app\common\model;

use think\Model;

class Teachchild extends Model {
    /**
     * 课件列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getTeachchildList($condition, $field = '*', $page = 0, $order = 't_id desc', $limit = '') {
        if($limit) {
            return db('teachchild')->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
        }else{
            $res= db('teachchild')->where($condition)->field($field)->order($order)->paginate($page,false,['query' => request()->param()]);
            $this->page_info=$res;
            return $res->items();
        }
    }

    //分页读取数据
    public function getPageTeachildList($where,$order,$page){
        $start = ($page-1)*50;
        $sql = "select * from x_teachchild where ".$where." order by ".$order." limit ".$start.",50";
        $result = $this->query($sql);
        return $result;
    }
    /**
     * 添加课件
     * @param array $insert
     * @return boolean
     */
    public function addTeachchild($insert) {
        return db('teachchild')->insert($insert);
    }
    /**
     * 取单个课件内容
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTeachchildInfo($condition, $field = '*') {
        return db('teachchild')->field($field)->where($condition)->find();
    }
    /**
     * 编辑课件
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editTeachchild($condition, $update) {
        return db('teachchild')->where($condition)->update($update);
    }

    public function delVideo($condition)
    {
        return db('teachchild')->where($condition)->delete();
    }

}