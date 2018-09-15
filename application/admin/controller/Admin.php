<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Admin extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
    }

    /**
     * 管理员列表
     */
    public function admin() {
        if (!request()->isPost()) {
            $admin_list = db('admin')->alias('a')->join('__GADMIN__ g', 'g.gid = a.admin_gid', 'LEFT')->paginate(10,false,['query' => request()->param()]);
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
        if (!request()->isPost()) {
            //得到权限组
            $gadmin = db('gadmin')->field('gname,gid')->select();
            $this->assign('gadmin', $gadmin);
            $this->setAdminCurItem('admin_add');
            return $this->fetch('admin_add');
        } else {
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = $_POST['gid'];
            $param['admin_password'] = md5($_POST['admin_password']);
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
                $list = $model_admin->infoAdmin($condition);
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
            //权限组名称验证
            case 'check_gadmin_name':
                $condition = array();
                if (is_numeric(input('param.gid'))) {
                    $condition['gid'] = array('neq', intval(input('param.gid')));
                }
                $condition['gname'] = input('get.gname');
                $info = db('gadmin')->where($condition)->find();
                if (!empty($info)) {
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
        $admin_id = intval(input('param.admin_id'));
        if (request()->isPost()) {
            //没有更改密码
            if ($_POST['new_pw'] != '') {
                $data['admin_password'] = md5($_POST['new_pw']);
            }
            $data['admin_gid'] = intval($_POST['gid']);
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
            $this->assign('admin', $admin);

            //得到权限组
            $gadmin = db('gadmin')->field('gname,gid')->select();
            $this->assign('gadmin', $gadmin);
            $this->setAdminCurItem('index');
            return $this->fetch('admin_edit');
        }
    }

    /**
     * 取得所有权限项
     *
     * @return array
     */
    private function permission() {
        $limit = $this->limitList();
        if (is_array($limit)) {
            foreach ($limit as $k => $v) {
                if (is_array($v['child'])) {
                    $tmp = array();
                    foreach ($v['child'] as $key => $value) {
                        $act = (!empty($value['act'])) ? $value['act'] : $v['act'];
                        if (strpos($act, '|') == false) {//act参数不带|
                            $limit[$k]['child'][$key]['op'] = rtrim($act . '.' . str_replace('|', '|' . $act . '.', $value['op']), '.');
                        } else {//act参数带|
                            $tmp_str = '';
                            if (empty($value['op'])) {
                                $limit[$k]['child'][$key]['op'] = $act;
                            } elseif (strpos($value['op'], '|') == false) {//op参数不带|
                                foreach (explode('|', $act) as $v1) {
                                    $tmp_str .= "$v1.{$value['op']}|";
                                }
                                $limit[$k]['child'][$key]['op'] = rtrim($tmp_str, '|');
                            } elseif (strpos($value['op'], '|') != false && strpos($act, '|') != false) {//op,act都带|，交差权限
                                foreach (explode('|', $act) as $v1) {
                                    foreach (explode('|', $value['op']) as $v2) {
                                        $tmp_str .= "$v1.$v2|";
                                    }
                                }
                                $limit[$k]['child'][$key]['op'] = rtrim($tmp_str, '|');
                            }
                        }
                    }
                }
            }
            return $limit;
        } else {
            return array();
        }
    }

    /**
     * 权限组
     */
    public function gadmin() {
        if (!request()->isPost()) {
            $list = db('gadmin')->paginate(10);
            $this->assign('list', $list->items());
            $this->assign('page', $list->render());
            $this->setAdminCurItem('gadmin');
            return $this->fetch('gadmin');
        } else {
            if (@in_array(1, $_POST['del_id'])) {
                $this->error(lang('admin_index_not_allow_del'));
            }
            if (!empty($_POST['del_id'])) {
                if (is_array($_POST['del_id'])) {
                    foreach ($_POST['del_id'] as $k => $v) {
                        db('gadmin')->where(array('gid' => intval($v)))->delete();
                    }
                }
                $this->log(lang('ds_del').lang('limit_gadmin') . '[ID:' . implode(',', $_POST['del_id']) . ']', 1);
                $this->success(lang('ds_common_del_succ'));
            } else {
                $this->error(lang('ds_common_del_fail'));
            }
        }
    }

    /**
     * 添加权限组
     */
    public function gadmin_add() {
        if (!request()->isPost()) {
            $this->assign('limit', $this->permission());
            $this->setAdminCurItem('gadmin_add');
            return $this->fetch('gadmin_add');
        } else {
            $limit_str = '';
            if (is_array($_POST['permission'])) {
                $limit_str = implode('|', $_POST['permission']);
            }
            $data['limits'] = encrypt($limit_str, MD5_KEY . md5($_POST['gname']));
            $data['gname'] = $_POST['gname'];
            if (db('gadmin')->insert($data)) {
                $this->log(lang('ds_add').lang('limit_gadmin') . '[' . $_POST['gname'] . ']', 1);
                $this->success(lang('ds_common_save_succ'), url('Admin/Admin/gadmin'));
            } else {
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }

    /**
     * 设置权限组权限
     */
    public function gadmin_set() {
        $gid = intval(input('param.gid'));
        $ginfo = db('gadmin')->where('gid', $gid)->find();
        if (empty($ginfo)) {
            $this->error(lang('admin_set_admin_not_exists'));
        }
        if (!request()->isPost()) {
            //解析已有权限
            $hlimit = decrypt($ginfo['limits'], MD5_KEY . md5($ginfo['gname']));
            $ginfo['limits'] = explode('|', $hlimit);
            $this->assign('ginfo', $ginfo);
            $this->assign('limit', $this->permission());
            $this->setAdminCurItem('index');
            return $this->fetch('gadmin_set');
        } else {
            $limit_str = '';
            if (is_array($_POST['permission'])) {
                $limit_str = implode('|', $_POST['permission']);
            }
            $limit_str = encrypt($limit_str, MD5_KEY . md5($_POST['gname']));
            $data['limits'] = $limit_str;
            $data['gname'] = $_POST['gname'];
            $update = db('gadmin')->where(array('gid' => $gid))->update($data);
            if ($update) {
                $this->log(lang('ds_edit').lang('limit_gadmin') . '[' . $_POST['gname'] . ']', 1);
                $this->success(lang('ds_common_save_succ'), url('Admin/Admin/gadmin'));
            } else {
                $this->error(lang('ds_common_save_succ'));
            }
        }
    }

    /**
     * 组删除
     */
    public function gadmin_del() {
        if (is_numeric(input('param.gid'))) {
            db('gadmin')->where(array('gid' => intval(input('param.gid'))))->delete();
            $this->log(lang('ds_del').lang('limit_gadmin') . '[ID' . intval(input('param.gid')) . ']', 1);
            redirect();
        } else {
            $this->error(lang('ds_common_op_fail'));
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
            ),
            array(
                'name' => 'gadmin',
                'text' => '权限组',
                'url' => url('Admin/Admin/gadmin')
            ),
            array(
                'name' => 'gadmin_add',
                'text' => '添加权限组',
                'url' => url('Admin/Admin/gadmin_add')
            ),
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
