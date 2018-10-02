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

        $logindata = db('school')->where(array('schoolid'=>$school_id))->select();

//        $studentinfo = db('student')->where(array('s_ownerAccount'=>$member_id))->select();
//        $studentinfo2 = db('student')->where(array('s_viceAccount'=>array('like', "%" . $member_id . "%")))->select();
//        if($studentinfo2){
//            $studentinfo = array_merge($studentinfo,$studentinfo2);
//        }
//        foreach ($studentinfo as $k=>$v){
//            $schoolinfo = db('school')->where(array('schoolid'=>$v['s_schoolid']))->find();
//            $logindata[$k] = $schoolinfo;
//        }

        if($logindata){
            output_data($logindata);
        }else{
            output_data(array());
        }


    }


}