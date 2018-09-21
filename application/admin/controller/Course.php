<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
/**
 * 套餐展示
 */
class Course extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/pkgs.lang.php');
    }

    public function course_manage(){
        $Course = model('Course');
        $condition = array();        
        $sc_list = $Course->get_course_List($condition, '10' ,'co_id asc');
        $this->assign('course_list', $sc_list);
        $this->assign('page', $Course->page_info->render());
        $this->setAdminCurItem('course_manage');
        return $this->fetch('course');
    }

    public function course_edit(){
        if (request()->isPost()) {
            $Course = Model('Course');
            $param =array();            
            $param['co_sort']  = intval(input('post.co_sort'));
            $param['co_type']   = trim(input('post.co_type'));
            $param['co_enabled']   = 1;
            $param['up_time']   = time();
            switch (input('actions')) {
                case 'edit':
                    $param['co_id'] = intval(input('param.co_id'));
                    $result = $Course->course_update($param);
                    if ($result) {
                        $this->log(lang('co_edit_succ') . '[' . input('post.co_type') . ']', null);
                        echo json_encode(['m'=>true,'ms'=>lang('co_edit_succ')]); 
                    }
                    break;                
                default:
                    $result = $Course->course_add($param);
                    if ($result) {
                        $this->log(lang('co_add_succ') . '[' . input('post.co_type') . ']', null);
                        echo json_encode(['m'=>true,'ms'=>lang('co_add_succ')]); 
                    }
                    break;
            }
            exit;

        }
    }

    /**
     *
     * 删除套餐
     */
    public function course_del() {
        $Course = Model('Course');
        /**
         * 删除套餐
         */
        $co_id = intval(input('param.co_id'));
        $result = $Course->course_del($co_id);

        if (!$result) {
            $this->error(lang('cl_del_fail'));
        } else {
            $this->log(lang('cl_del_succ') . '[' . $co_id . ']', null);
            $this->success(lang('cl_del_succ'));
        }
    }



    /**
     *
     * ajaxOp
     */
    public function ajax() {
        switch ($_GET['branch']) {
            case 'co_enabled':
                $Course = Model('Course');
                $param[trim($_GET['column'])] = intval($_GET['value']);
                $param['co_id'] = intval($_GET['id']);
                $Course->course_update($param);
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
                'name' => 'course_manage',
                'text' => lang('course_manage'),
                'url' => url('Admin/Course/course_manage')
            )
        );
        
        return $menu_array;
    }

}

?>
