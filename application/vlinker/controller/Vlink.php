<?php

namespace app\vlinker\controller;

use think\Lang;

class Vlink extends BaseController {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'wumeng/lang/zh-cn/index.lang.php');
    }

    /**
     * 获取学校列表
     */
    public function GetSchoolList(){
    	$input = input();
    	$School = model('School');
    	$Class = model('Classes');
    	$areaid =$input['areaid'];
    	$level  =$input['level'];
    	if ($level==3) {//获取学校列表
    		$condition = array(
	    		'areaid' => $areaid,
	    		'isdel'  => 1,
	    	);
	    	$field = 'schoolid as res_group_id,name as res_group_name';
	    	$schoolList = $School->getAllAchool($condition,$field);
	    	output_data($schoolList);
    	}
    	if ($level > 3) {//获取班级列表
    		
    		$schoolList = $Class->getClassInfoBySchool($areaid);
    		output_data($schoolList);

    	}
    	
    }

}