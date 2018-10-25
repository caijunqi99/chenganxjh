<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Schoolapply extends MobileMember
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    //申请
    public function addapply(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('会员id不能为空');
        }
        $memberInfo = db("member")->where(array('member_id'=>$member_id))->find();
        if(empty($memberInfo)){
            output_error('无此会员');
        }
        $data = array();
        $data['member_id'] = $member_id;
        $data['provinceid'] = trim(input('post.provinceid'));
        $data['cityid'] = trim(input('post.cityid'));
        $data['areaid'] = trim(input('post.areaid'));
        $data['region'] = input('post.region');
        $data['schoolname'] = input('post.schoolname');
        $data['username'] = input('post.username');
        $data['phone'] = input('post.phone');
        $data['message'] = input('post.message');
        $data['createtime'] = time();
        $schoolapply = model('Schoolapply');
        $result = $schoolapply->addSchoolapply($data);

        if ($result) {
            output_data("申请成功");
        } else {
            output_error('申请失败');
        }
    }

    //申请信息展示
    public function info(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('会员id不能为空');
        }
        $schoolapply = model('Schoolapply');
        $schoolinfo = $schoolapply->getSchoolapplyInfo(array('member_id'=>$member_id));
        //地区范围
        $parent = db('area')->field("area_id,area_parent_id,area_name,area_deep")->where(array('area_deep'=>1))->select();
        $child = db('area')->field("area_id,area_parent_id,area_name,area_deep")->where(array('area_deep'=>2))->select();
        $child3 = db('area')->field("area_id,area_parent_id,area_name,area_deep")->where(array('area_deep'=>3))->select();
        foreach($parent as $key=>$val){
            foreach($child as $k=>$v){
                if($v['area_parent_id']==$val['area_id']){
                    $parent[$key]['childTwo'][] = $v;
                }
            }
        }
        foreach($parent as $key=>$item){
            foreach($item['childTwo'] as $k2=>$v2){
                foreach($child3 as $k3=>$v3){
                    if($v3['area_parent_id']==$v2['area_id']){
                        $item['childTwo'][$k2]['childThree'][] = $v3;
                    }
                }
            }
            $parent[$key]['childTwo'] = $item['childTwo'];
        }
        $schoolinfo['area'] = $parent;
        output_data([$schoolinfo]);
    }

}