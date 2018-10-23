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

    /**
     * 获取学校 校车信息
     */
    public function GetSchoolBusInfo(){
        $childId = input('post.child_id');
        $Student = model('Student');
        $childInfo = $Student->getChildrenInfoById($childId);
        $Bus = model('Schoolbus');
        $condition = array(
            
        );
        $businfo = $Bus->get_schoolbus_List($condition);

    }

    /**
     * 获取学校 食谱信息
     */
    public function GetSchoolFoodInfo(){

    }


}