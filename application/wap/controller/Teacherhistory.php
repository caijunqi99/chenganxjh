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
    public function lists(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('member_id参数有误');
        }
        $teachhistory = model('Teachhistory');
        $condition=array();
        $condition['t_userid']=$member_id;
        $condition['t_del']=1;
        $result=$teachhistory->getTeachhistoryList($condition);
        foreach($result as $k=>$v){
            $result[$k]['date'] = date("Y-m-d",$v['t_maketime']);
            $videoinfo = db('teachchild')->where(array('t_id'=>$v['t_tid']))->find();
            $result[$k]['title'] = $videoinfo['t_title'];
            $result[$k]['videoimg'] = $videoinfo['t_videoimg'];
            $result[$k]['videourl'] = $videoinfo['t_url'];
            $result[$k]['author'] = $videoinfo['t_author'];
        }
        foreach($result as $key=>$item){
            $data[$item['date']][] = $item;
        }
        output_data($data);
    }

    //添加观看历史记录
    public function addhistory(){
        $tid = intval(input('post.tid'));
        $member_id = intval(input('post.member_id'));
        $teachhistory = model('Teachhistory');
        $condition=array();
        $condition['t_tid']=$tid;
        $condition['t_userid']=$member_id;
        $res=$teachhistory->getTeachhistoryInfo($condition);
        $condition['t_maketime'] = time();
        if($res){
            $result = $teachhistory->editTeachhistory(array('t_id'=>$res['t_id']),$condition);
        }else {
            $result = $teachhistory->addTeachhistory($condition);
        }
        if ($result) {
            output_data(array('data'=>"添加成功"));
        } else {
            output_error('添加失败');
        }
    }

}