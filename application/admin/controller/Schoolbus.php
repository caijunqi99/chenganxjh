<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
/**
 * 套餐展示
 */
class Schoolbus extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/schoolbus.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    public function schoolbus_manage1(){
        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $Schoolbus = model('Schoolbus');
        $condition = array();        
        $bus_list = $Schoolbus->get_schoolbus_List($condition, '10' ,'bus_id asc');

        foreach ($bus_list as $k => &$v) {
            $v['bus_linea']=json_decode($v['bus_line'],TRUE);
        }
        unset($v);
        // p($bus_list);exit;
        $this->assign('bus_list', $bus_list);
        $this->assign('page', $Schoolbus->page_info->render());
        $this->setAdminCurItem('schoolbus_manage');
        return $this->fetch('bus');
    }

    public function schoolbus_manage(){
        
        return $this->fetch('bus_edit');   
    }


    public function schoolbus_edit(){
        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        
        if (request()->isPost()) {
            $Schoolbus = Model('Schoolbus');
            $param =array();
            $input = input();
            $param['bus_card']       = input('post.bus_card');
            $param['bus_line_name']  = input('post.bus_line_name');
            $param['bus_color']      = input('post.bus_color');
            $param['bus_desc']       = input('post.bus_desc');
            $param['bus_start']      = input('post.bus_start');
            $param['bus_end']        = input('post.bus_end');
            $param['bus_start_time'] = input('post.bus_start_time');
            $param['bus_end_time']   = input('post.bus_end_time');            
            $param['up_time']        = time();

            $bus_repeat ='';
            foreach ($input['week'] as $key => $w) {
                $bus_repeat .= $w['week'].',';
            }
            $param['bus_repeat'] = trim($bus_repeat,',');
            $bus_line = array();
            foreach ($input['bus_line'] as $k => $v) {
                $bus_line[$k]['Station'] =$v[0];
                $bus_line[$k]['ArrivalTime'] =$v[1];
            }
            $param['bus_line'] = json_encode($bus_line);

            switch (input('actions')) {
                case 'edit'://改
                    $param['bus_id'] = intval(input('param.bus_id'));
                    $result = $Schoolbus->schoolbus_update($param);
                    if ($result) {
                        $this->log(lang('bus_edit_succ') . '[' . input('post.bus_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('bus_edit_succ')]);
                    }
                    break; 
                case 'del'://删
                    $del=array(
                        'bus_id' =>intval(input('param.bus_id')),
                        'is_del' =>2
                    );
                    $result = $Schoolbus->schoolbus_update($del);
                    if ($result) {
                        $this->log(lang('bus_del_succ') . '[' . input('post.bus_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('bus_del_succ')]);
                    }
                    break;               
                default://增
                    $param['sc_id']   = 1;          
                    $result = $Schoolbus->schoolbus_add($param);
                    if ($result) {
                        $this->log(lang('bus_add_succ') . '[' . input('post.bus_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('bus_add_succ')]);
                    }
                    break;
            }
            exit;

        }
    }



    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'schoolbus_manage',
                'text' => lang('schoolbus_manage'),
                'url' => url('Admin/Course/schoolbus_manage')
            )
        );
        
        return $menu_array;
    }

}

?>
