<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Teacherhistory
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    //用户历史记录列表
    public function list(){
        $member_id = intval(input('post.member_id'));
        $condition=array();
        $condition['t_userid']=$member_id;
        $condition['t_del']=1;
        $teachhistory = model('Teachhistory');
        $result=$teachhistory->getTeachhistoryList($condition);

//        foreach($result as $key=>$v){
//
//        }
        output_data($result);
    }
}