<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
use vomont\Vomont;

class Monitor extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/look.lang.php');
        //获取省份
        $province = db('area')->field('area_id,area_parent_id,area_name')->where('area_parent_id=0')->select();
        //获取学校
        $school = db('school')->field('schoolid,name')->select();
//获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);

        $this->assign('school',$school);
        $this->assign('province',$province);
    }

    /**
     * @desc 网络监控
     * @author 郎志耀
     * @time 20180926
     */
    public function monitor(){
        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        if($_POST){
            $schoolid = intval($_POST['school']);
            $grade=intval($_POST['grade']);
            $classid=intval($_POST['class']);
            $model_school = Model('school');
            $schoolres=$model_school->getSchoolById($schoolid);
            $shu=array();
            if($schoolres['res_group_id']!=0) {
                $shu[] = $schoolres['res_group_id'];
            }
            $model_class=Model('classes');
            if($classid!=0){
                $classres=$model_class->getClassById($classid);
                if($classres['res_group_id']!=0) {
                    $shu[] = $classres['res_group_id'];
                }
            }else{
                $condtion=array();
                if($grade!=0){
                    $condtion['typeid']=$grade;
                }
                $condtion['schoolid']=$schoolid;
                $condtion['isdel']=1;
                $classres=$model_class->getAllClasses($condtion);
                foreach($classres as $v){
                    if($v['res_group_id']!=0) {
                        $shu[] = $v['res_group_id'];
                    }
                }
            }
            $vlink = new Vomont();
            $res= $vlink->SetLogin();
            $accountid=$res['accountid'];
            $data='';
            foreach($shu as $v){
                $datas=$vlink->SetPlay($accountid,$v);
                if(empty($data)) {
                    $data = $datas['resources'];
                }else{
                    $data[] = $datas['resources'];
                }
            }
            foreach($data as $v){
                $a.=$v['deviceid'].'-'.$v['channelid'].',';
            }
            $video=$vlink->Resources($accountid,$a);
            $camera=$video['channels'];
            foreach($camera as $k=>$v){
                foreach($data as $item){
                    if($item['channelid']==$v['channelid']){
                        $camera[$k]['cameraname']=$item['name'];
                    }
                }
            }
            $this->assign('video', $camera);
            $this->assign('schoolname',$schoolres['name']);
        }
        $this->setAdminCurItem('monitor');
        return $this->fetch('monitor');
    }

}