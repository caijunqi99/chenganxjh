<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
/**
 * 套餐展示
 */
class Schoolfood extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/schoolfood.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    public function schoolfood_manage1(){
        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $Schoolfood = model('Schoolfood');
        $condition = array();        
        $food_list = $Schoolfood->get_schoolfood_List($condition, '10' ,'food_id asc');

        foreach ($food_list as $k => &$v) {
            $v['food_linea']=json_decode($v['food_line'],TRUE);
        }
        unset($v);
        p($food_list);exit;
        $this->assign('food_list', $food_list);
        $this->assign('page', $Schoolfood->page_info->render());
        $this->setAdminCurItem('schoolfood_manage');
        return $this->fetch('schoolfood');
    }

    public function schoolfood_manage(){
        
        return $this->fetch('food_edit');
    }


    public function schoolfood_edit(){
        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        p(input());exit;
        if (request()->isPost()) {
            $Schoolfood = Model('Schoolfood');
            $param =array();
            $param['food_card']       = input('post.food_card');
            $param['food_line_name']  = input('post.food_line_name');
            $param['food_color']      = input('post.food_color');
            $param['food_desc']       = input('post.food_desc');
            $param['food_start']      = input('post.food_start');
            $param['food_end']        = input('post.food_end');
            $param['food_start_time'] = input('post.food_start_time');
            $param['food_end_time']   = input('post.food_end_time');

            
            $param['food_line']       = input('post.food_line');
            $param['food_repeat']     = input('post.food_repeat');
            $param['up_time']        = time();
            p($param);exit;
            switch (input('actions')) {
                case 'edit'://改
                    $param['food_id'] = intval(input('param.food_id'));
                    $result = $Schoolfood->schoolfood_update($param);
                    if ($result) {
                        $this->log(lang('food_edit_succ') . '[' . input('post.food_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('food_edit_succ')]);
                    }
                    break; 
                case 'del'://删
                    $param['food_id'] = intval(input('param.food_id'));
                    $param['is_del'] = 2;
                    $result = $Schoolfood->schoolfood_update($param);
                    if ($result) {
                        $this->log(lang('food_del_succ') . '[' . input('post.food_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('food_del_succ')]);
                    }
                    break;               
                default://增
                    $result = $Schoolfood->schoolfood_add($param);
                    if ($result) {
                        $this->log(lang('food_add_succ') . '[' . input('post.food_card') . ']', null);
                        echo json_encode(['m' => true, 'ms' => lang('food_add_succ')]);
                    }
                    break;
            }
            exit;

        }
    }




    /**
     *
     * ajaxOp
     */
    public function ajax() {
        switch ($_GET['branch']) {
            case 'food_enabled':
                $Schoolfood = Model('Schoolfood');
                $param[trim($_GET['column'])] = intval($_GET['value']);
                $param['food_id'] = intval($_GET['id']);
                $Schoolfood->schoolfood_update($param);
                echo 'true';
                exit;
                break;
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'schoolfood_manage',
                'text' => lang('schoolfood_manage'),
                'url' => url('Admin/Course/schoolfood_manage')
            )
        );
        
        return $menu_array;
    }

}

?>
