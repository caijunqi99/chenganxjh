<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Teacherhistory extends MobileMember
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
        $page = !empty(input('post.page'))?input('post.page'):1;
        $result=$teachhistory->getTeachhistoryList($condition,'',$page,'t_id desc',10);
        foreach($result as $k=>$v){
            $result[$k]['date'] = date("Y-m-d",$v['t_maketime']);
            if(date("Y-m-d",time()) == date("Y-m-d",$v['t_maketime'])){
                $result[$k]['date'] = "今天";
            }
            $videoinfo = db('teachchild')->where(array('t_id'=>$v['t_tid']))->find();
            $result[$k]['title'] = $videoinfo['t_title'];
            $result[$k]['videoimg'] = $videoinfo['t_videoimg'];
            $result[$k]['videourl'] = $videoinfo['t_url'];
            $result[$k]['author'] = $videoinfo['t_author'];
        }
        foreach($result as $key=>$item){
            $data[$item['date']][] = $item;
        }
        $datas[] = $data;
        output_data($datas);
    }

    //添加观看历史记录
    public function addhistory(){
        $tid = intval(input('post.tid'));
        $member_id = intval(input('post.member_id'));
        if(empty($member_id) || empty($tid)){
            output_error('参数有误');
        }
        $teachhistory = model('Teachhistory');
        $condition=array();
        $condition['t_tid']=$tid;
        $condition['t_userid']=$member_id;
        $condition['t_del']=1;
        $res=$teachhistory->getTeachhistoryInfo($condition);
        $condition['t_maketime'] = time();
        if($res){
            $result = $teachhistory->editTeachhistory(array('t_id'=>$res['t_id']),$condition);
        }else {
            $result = $teachhistory->addTeachhistory($condition);
        }
        if ($result) {
            output_data([array('data'=>"添加成功")]);
        } else {
            output_error('添加失败');
        }
    }

    //删除观看历史
    public function delhistory(){
        $all = intval(input('post.all'));//全部删除
        $ids = input('post.ids');//多个删除 逗号分隔
        $userid = intval(input('post.userid'));
        if(empty($ids) || empty($userid)){
            output_error('参数有误');
        }
        if($all==1){
            $where = "t_userid={$userid} and t_del=1";
        }else{
            $where = "t_userid={$userid} and t_id in ({$ids})";
        }
        $teachhistory = model('Teachhistory');
        $result = $teachhistory->editTeachhistory($where,array('t_del'=>2));
        if ($result) {
            output_data([array('data'=>"删除成功")]);
        } else {
            output_error('删除失败');
        }
    }

}