<?php

namespace app\wap\controller;

use think\captcha\Captcha;

class Schooldesc {


    /**
     * 学校简介
     * $member 会员id
     */
    public function detailinfo() {

        $member_id  = intval(input('post.member_id'));
        $school_id  = intval(input('post.school_id'));
        if (empty($member_id)||empty($school_id)) {
            output_error('参数有误');
        }
        $school=model('school');
        $logindata = $school->getSchoolById($school_id);
        $desc=model('Schooldesc');
        $where=array();
        $where['s_sid']=$school_id;
        $data=$desc->getDescInfo($where);
        $data['name']=$logindata['name'];
        $data['region']=$logindata['region'];
        $data['address']=$logindata['address'];
        if($data){
            output_data($data);
        }else{
            output_data(array());
        }


    }


}