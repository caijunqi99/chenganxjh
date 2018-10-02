<?php

namespace app\common\model;

use think\Model;

class Mood extends Model {

    /**
     * 查询所有系统文章
     */
    public function getList($where) {
        return db('mood')->where($where)->order('id desc')->select();
    }

    /**
     * 根据编号查询一条
     * 
     * @param unknown_type $id
     */
    public function getOneById($id) {
        return db('mood')->where('doc_id',$id)->find();
    }

    /**
     * 根据标识码查询一条
     * 
     * @param unknown_type $id
     */
    public function getOneByCode($code) {
        return db('mood')->where('doc_code',$code)->find();
    }

    /**
     * 更新
     * 
     * @param unknown_type $param
     */
    public function update1($param) {
        return db('mood')->where('doc_id',$param['doc_id'])->update($param);
    }

    //指定日期几天前
    function getnum($time)
    {
        //获取今天凌晨的时间戳
        $day = strtotime(date('Y-m-d',time()));
        //获取昨天凌晨的时间戳
        $pday = strtotime(date('Y-m-d',strtotime('-1 day')));
        //获取现在的时间戳
        $nowtime = time();

        $tc = $nowtime-$time;
        if($time<$pday){
            $str = date('Y-m-d H:i:s',$time);
        }elseif($time<$day && $time>$pday){
            $str = "昨天";
        }elseif($tc>60*60){
            $str = floor($tc/(60*60))."小时前";
        }elseif($tc>60){
            $str = floor($tc/60)."分钟前";
        }else{
            $str = "刚刚";
        }
        return $str;
    }
}

?>
