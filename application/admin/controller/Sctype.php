<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
/**
 * 套餐展示
 */
class Sctype extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/pkgs.lang.php');
    }

    public function sctype_manage(){
        $pkg = model('Schooltype');
        $condition = array();        
        $sc_list = $pkg->get_sctype_List($condition, '10' ,'sc_id asc');
        $this->assign('sctype_list', $sc_list);
        $this->assign('page', $pkg->page_info->render());
        $this->setAdminCurItem('sctype_manage');
        return $this->fetch('sctype');
    }

    public function sctype_edit(){
        if (request()->isPost()) {
            $Sctype = Model('Schooltype');
            $param =array();            
            $param['sc_sort']  = intval(input('post.sc_sort'));
            $param['sc_type']   = trim(input('post.sc_type'));
            $param['sc_enabled']   = 1;
            $param['up_time']   = time();
            switch (input('actions')) {
                case 'edit':
                    $param['sc_id'] = intval(input('param.sc_id'));
                    $result = $Sctype->sctype_update($param);
                    if ($result) {
                        $this->log(lang('sc_edit_succ') . '[' . input('post.sc_type') . ']', null);
                        echo json_encode(['m'=>true,'ms'=>lang('sc_edit_succ')]); 
                    }
                    break;                
                default:
                    $result = $Sctype->sctype_add($param);
                    if ($result) {
                        $this->log(lang('sc_add_succ') . '[' . input('post.sc_type') . ']', null);
                        echo json_encode(['m'=>true,'ms'=>lang('sc_add_succ')]); 
                    }
                    break;
            }
            exit;

        }
        
        return $this->fetch('course_edit');
    }

    /**
     *
     * 删除套餐
     */
    public function sctype_del() {
        $Sctype = Model('Schooltype');
        /**
         * 删除套餐
         */
        $sc_id = intval(input('param.sc_id'));
        $result = $Sctype->sctype_del($sc_id);

        if (!$result) {
            $this->error(lang('pkg_del_fail'));
        } else {
            $this->log(lang('pkg_del_succ') . '[' . $sc_id . ']', null);
            $this->success(lang('pkg_del_succ'));
        }
    }



    /**
     *
     * ajaxOp
     */
    public function ajax() {
        switch ($_GET['branch']) {
            case 'sc_enabled':
                $Sctype = Model('Schooltype');
                $param[trim($_GET['column'])] = intval($_GET['value']);
                $param['sc_id'] = intval($_GET['id']);
                $Sctype->sctype_update($param);
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
                'name' => 'sctype_manage',
                'text' => lang('sctype_manage'),
                'url' => url('Admin/Sctype/sctype_manage')
            )
        );
        
        return $menu_array;
    }

}

?>
