<?php

namespace app\wap\controller;

use think\captcha\Captcha;

class Students extends MobileMember{

    /**
     * 学校简介
     * $member 会员id
     */
    public function get_student_info() {

        $member_id  = $this->member_info;
        $Student = model('Student');
        $childs = $Student->getAllChilds($this->member_info['member_id']);
        output_data($childs);



    }


}