<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Admin extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    /**
     * 管理员列表
     */
    public function admin() {
        $admin_id = $this->admin_info['admin_id'];
        if (!request()->isPost()) {
            $admin_list = db('admin')->alias('a')->join('__GADMIN__ g', 'g.gid = a.admin_gid', 'LEFT')->where("a.create_uid = $admin_id")->paginate(10,false,['query' => request()->param()]);
            $this->assign('admin_list', $admin_list->items());
            $this->assign('page', $admin_list->render());
            $this->setAdminCurItem('admin');
            return $this->fetch('admin');
        } else {

            //ID为1的会员不允许删除
            if (@in_array(1, $_POST['del_id'])) {
                $this->error(lang('admin_index_not_allow_del'));
            }
            if (!empty($_POST['del_id'])) {
                if (is_array($_POST['del_id'])) {
                    foreach ($_POST['del_id'] as $k => $v) {
                        db('admin')->where(array('admin_id' => intval($v)))->delete();
                    }
                }
                $this->log(lang('ds_del').lang('limit_admin'), 1);
                $this->error(lang('ds_common_del_succ'));
            } else {
                $this->error(lang('ds_common_del_succ'));
            }
        }
    }

    /**
     * 管理员删除
     */
    public function admin_del() {
        $admin_id = intval(input('param.admin_id'));
        if (!empty($admin_id)) {
            if ($admin_id == 1) {
                $this->error(lang('ds_common_save_fail'));
            }
            db('admin')->where(array('admin_id' => $admin_id))->delete();
            $this->log(lang('ds_del').lang('limit_admin') . '[ID:' . $admin_id . ']', 1);
            $this->success(lang('ds_common_del_succ'));
        } else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * 管理员添加
     */
    public function admin_add() {
        $admin_id = $this->admin_info['admin_id'];
        if (!request()->isPost()) {
            //得到权限组
            $gadmin = db('gadmin')->field('gname,gid')->where("create_uid = $admin_id")->select();
            $this->assign('gadmin', $gadmin);
            $this->setAdminCurItem('admin_add');
            return $this->fetch('admin_add');
        } else {
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = $_POST['gid'];
            $param['admin_password'] = md5($_POST['admin_password']);
            $param['create_uid'] = $admin_id;
            $param['admin_company_id'] = $_POST['admin_company_id'];
            $param['admin_phone'] = $_POST['admin_phone'];
            $param['admin_truename'] = $_POST['admin_truename'];
            $param['admin_department'] = $_POST['admin_department'];
            $param['admin_description'] = $_POST['admin_description'];
            $rs = $model_admin->addAdmin($param);
            if ($rs) {
                $this->log(lang('ds_add').lang('limit_admin') . '[' . $_POST['admin_name'] . ']', 1);
                $this->success(lang('ds_common_save_succ'), url('Admin/Admin/admin'));
            } else {
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }

    /**
     * ajax操作
     */
    public function ajax() {
        switch (input('get.branch')) {
            //管理人员名称验证
            case 'check_admin_name':
                $model_admin = Model('admin');
                $condition['admin_name'] = input('get.admin_name');
//                $condition['create_uid'] = $this->admin_info['admin_id'];
//                $list = $model_admin->infoAdmin($condition);
                $list = $model_admin->where($condition)->find();
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
            case 'check_admin_phone':
                $model_admin = Model('admin');
                $condition['admin_phone'] = input('get.admin_phone');
//                $condition['create_uid'] = $this->admin_info['admin_id'];
//                $list = $model_admin->infoAdmin($condition);
                $list = $model_admin->where($condition)->find();
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
            case 'check_admin_name_edit':
                $model_admin = Model('admin');
                $condition['admin_name'] = input('get.admin_name');
                $condition['admin_id'] = array('neq', intval(input('get.admin_id')));
//                $condition['create_uid'] = $this->admin_info['admin_id'];
//                $list = $model_admin->infoAdmin($condition);
                $list = $model_admin->where($condition)->find();
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
            case 'check_admin_phone_edit':
                $model_admin = Model('admin');
                $condition['admin_phone'] = input('get.admin_phone');
                $condition['admin_id'] = array('neq', intval(input('get.admin_id')));
//                $condition['create_uid'] = $this->admin_info['admin_id'];
//                $list = $model_admin->infoAdmin($condition);
                $list = $model_admin->where($condition)->find();
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
        }
    }

    /**
     * 设置管理员权限
     */
    public function admin_edit() {
        $admin_userid = $this->admin_info['admin_id'];
        $admin_id = intval(input('param.admin_id'));
        if (request()->isPost()) {
            //没有更改密码
            if ($_POST['new_pw'] != '') {
                $data['admin_password'] = md5($_POST['new_pw']);
            }
            $data['admin_gid'] = intval($_POST['gid']);
            $data['create_uid'] = $admin_userid;
            $data['admin_company_id'] = intval($_POST['admin_company_id']);
            $data['admin_phone'] = $_POST['admin_phone'];
            $data['admin_true_name'] = $_POST['admin_truename'];
            $data['admin_department'] = $_POST['admin_department'];
            $data['admin_description'] = $_POST['admin_description'];
            //查询管理员信息
            $admin_model = Model('admin');
            $result = $admin_model->updateAdmin($data,$admin_id);
            if ($result) {
                $this->log(lang('ds_edit').lang('limit_admin') . '[ID:' . $admin_id . ']', 1);
                $this->success(lang('admin_edit_success'), url('Admin/Admin/admin'));
            } else {
                $this->error(lang('admin_edit_fail'), url('Admin/Admin/admin'));
            }
        } else {
            //查询用户信息
            $admin_model = Model('admin');
            $admin = $admin_model->getOneAdmin($admin_id);
            if (!is_array($admin) || count($admin) <= 0) {
                $this->error(lang('admin_edit_admin_error'), url('Admin/Admin/admin'));
            }
//            halt($admin);
            $this->assign('admin', $admin);

            //得到权限组
            $gadmin = db('gadmin')->field('gname,gid')->where("create_uid = $admin_userid")->select();
            $this->assign('gadmin', $gadmin);
            $this->setAdminCurItem('admin');
            return $this->fetch('admin_edit');
        }
    }



    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'admin',
                'text' => '管理员',
                'url' => url('Admin/Admin/admin')
            ),
            array(
                'name' => 'admin_add',
                'text' => '添加管理员',
                'url' => url('Admin/Admin/admin_add')
            )
        );
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Admin/edit')
            );
        }
        return $menu_array;
    }

}

?>
