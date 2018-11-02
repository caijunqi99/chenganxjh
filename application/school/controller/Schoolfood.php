<?php

namespace app\school\controller;

use think\Lang;
use think\Validate;
/**
 * 套餐展示
 */
class Schoolfood extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'school/lang/zh-cn/admin.lang.php');
        Lang::load(APP_PATH . 'school/lang/zh-cn/schoolfood.lang.php');
    }

    public function schoolfood_manage1(){
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_gid']!=5){
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
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_gid']!=5){
            $this->error(lang('ds_assign_right'));
        }
        return $this->fetch('food_edit');
    }


    public function schoolfood_edit(){
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_gid']!=5){
            $this->error(lang('ds_assign_right'));
        }
        if (request()->isPost()) {
            $Schoolfood = Model('Schoolfood');
            $param =array();
            $input = input();
            $food_class      = input('post.food_class');
            $food_id      = input('post.food_id');
            $foodContent = $input['food_content'];            
            if($food_id){
                $param['food_class']   = input('post.food_class');
                $param['food_name']    = input('post.food_name');
                $param['food_content'] = input('post.food_content');
                $param['food_desc']    = input('post.food_desc');
                $param['up_time']      = time();
            }else{
                foreach ($foodContent as $k => $v) {
                    $param[$k] = array(
                        'food_class'   => $food_class,
                        'food_name'    => $v[0],
                        'food_content' => $v[1],
                        'food_desc'    => $v[2],
                        'up_time'      => time(),
                        'sc_id'        => 1
                    );
                }                                           
            }
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
                    $del=array(
                        'food_id' =>intval(input('param.food_id')),
                        'is_del' =>2
                    );
                    $result = $Schoolfood->schoolfood_update($del);
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
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'schoolfood_manage',
                'text' => lang('schoolfood_manage'),
                'url' => url('School/Course/schoolfood_manage')
            )
        );
        
        return $menu_array;
    }

}

?>
