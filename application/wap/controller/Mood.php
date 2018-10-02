<?php

namespace app\wap\controller;

use think\captcha\Captcha;

class Mood {


    /**
     * app心情列表
     */
    public function mood() {
        $member_id  = intval(input('post.member_id'));
        if (empty($member_id)) {
            output_error('参数有误');
        }

        $model_mood = Model('mood');
        $data = $model_mood->getList(array('member_id'=>$member_id));

        if($data){
            $memberinfo = db('member')->where(array('member_id'=>$member_id))->find();
            $logindata['member_nickname'] = $memberinfo['member_nickname'];
            $logindata['member_avatar'] = $memberinfo['member_avatar'];
        }
        foreach ($data as $k=>$v){
            $data[$k]['pubtime'] = date("Y-m-d H:i:s",$v['pubtime']);
            $data[$k]['ago'] = $model_mood->getnum($v['pubtime']);
        }

        $logindata['mood'] = $data;

        if($logindata){
            output_data($logindata);
        }else{
            output_data(array());
        }


    }



}