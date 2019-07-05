<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Teachtype extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/teacher.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name=explode('\\',__CLASS__);
        $class_name = strtolower(end($class_name));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    /**
     * 分类管理
     */
    public function index() {
        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_type = model('Teachtype');
        if (request()->isPost()) {
            //删除
            if ($_POST['submit_type'] == 'del') {
                $gcids = implode(',', $_POST['check_gc_id']);
                if (!empty($_POST['check_gc_id'])) {
                    if (!is_array($_POST['check_gc_id'])) {
                        $this->log(lang('ds_del,goods_class_index_class') . '[ID:' . $gcids . ']', 0);
                        $this->error(lang('ds_common_del_fail'));
                    }
                    $del_array = $model_type->delGoodsClassByGcIdString($gcids);
                    $this->log(lang('ds_del,goods_class_index_class') . '[ID:' . $gcids . ']', 1);
                    $this->success(lang('ds_common_del_succ'));
                } else {
                    $this->log(lang('ds_del,goods_class_index_class') . '[ID:' . $gcids . ']', 0);
                    $this->error(lang('ds_common_del_fail'));
                }
            }
        }

        //父ID
        $parent_id = input('param.gc_parent_id') ? intval(input('param.gc_parent_id')) : 0;

        //列表
        $tmp_list = $model_type->getTreeTypeList(4);
        $class_list = array();
        if (is_array($tmp_list)) {
            foreach ($tmp_list as $k => $v) {
                if ($v['gc_parent_id'] == $parent_id) {
                    //判断是否有子类
                    if (isset($tmp_list[$k + 1]['deep']) && $tmp_list[$k + 1]['deep'] > $v['deep']) {
                        $v['have_child'] = 1;
                    }
                    $class_list[] = $v;
                }
            }
        }
        if (input('param.ajax') == '1') {
            $output = json_encode($class_list);
            echo $output;
            exit;
        } else {
            $this->assign('class_list', $class_list);
            $this->setAdminCurItem('index');
            return $this->fetch();
        }
    }

    /**
     * 分类添加
     */
    public function type_class_add() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_class = model('Teachtype');
        if (!request()->isPost()) {
            //父类列表，只取到第二级
            $parent_list = $model_class->getTreeTypeList(3);
            $gc_list = array();
            if (is_array($parent_list)) {
                foreach ($parent_list as $k => $v) {
                    $parent_list[$k]['gc_name'] = str_repeat("&nbsp;", $v['deep'] * 3) . $v['gc_name'];
                    if ($v['deep'] == 1)
                        $gc_list[$k] = $v;
                }
            }
            $this->assign('gc_list', $gc_list);
            $this->assign('parent_list', $parent_list);
            $this->setAdminCurItem('type_class_add');
            return $this->fetch();
        } else {

            $insert_array = array();
            $insert_array['gc_name'] = input('post.gc_name');
            $insert_array['gc_parent_id'] = intval(input('post.gc_parent_id'));
            $insert_array['gc_sort'] = intval(input('post.gc_sort'));
            $admininfo = $this->getAdminInfo();
            $insert_array['option_id'] = $admininfo['admin_id'];
            $insert_array['option_time'] = time();
            //验证数据  BEGIN
            $rule = [
                ['gc_name', 'require', '分类标题为必填'],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($insert_array);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END

            $result = $model_class->addTypeClass($insert_array);
            if ($result) {
                /*if ($insert_array['gc_parent_id'] == 0) {
                    $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_COMMON;
                    if (!empty($_FILES['pic']['name'])) {//上传图片
                        $file = request()->file('pic');
                        $result = $file->validate(['ext' => 'jpg,png,gif'])->move($upload_file, 'category-pic-' . $result . '.jpg');
                    }
                }*/
                $this->log(lang('ds_add').lang('goods_class_index_class') . '[' . $_POST['gc_name'] . ']', 1);
                $this->success(lang('ds_common_save_succs'), url('Admin/Teachtype/index'));
            } else {
                $this->log(lang('ds_add').lang('goods_class_index_class') . '[' . $_POST['gc_name'] . ']', 0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }

    /**
     * 编辑
     */
    public function type_class_edit() {
        $model_class = model('Teachtype');
        $gc_id = intval(input('param.gc_id'));

        if (!request()->isPost()) {
            $class_array = $model_class->getTeachInfoById($gc_id);
            if (empty($class_array)) {
                $this->error(lang('goods_class_batch_edit_paramerror'));
            }

            //父类列表，只取到第三级
            $parent_list = $model_class->getTreeTypeList(3);
            if (is_array($parent_list)) {
                foreach ($parent_list as $k => $v) {
                    $parent_list[$k]['gc_name'] = str_repeat("&nbsp;", $v['deep'] * 3) . $v['gc_name'];
                }
            }
            $this->assign('parent_list', $parent_list);
            $this->assign('class_array', $class_array);
            $this->setAdminCurItem('type_class_edit');
            return $this->fetch();
        } else {
            $update_array = array();
            $update_array['gc_name'] = input('post.gc_name');
            $update_array['gc_sort'] = intval($_POST['gc_sort']);
            $update_array['gc_parent_id'] = intval(input('post.gc_parent_id'));

            //验证数据  BEGIN
            $rule = [
                ['gc_name', 'require', '分类标题为必填'],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($update_array);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END


            // 更新分类信息
            $where = array('gc_id' => $gc_id);
            $result = $model_class->editTypeClass($update_array, $where);
            if (!$result) {
                $this->log(lang('ds_edit').lang('goods_class_index_class') . '[' . $_POST['gc_name'] . ']', 0);
                $this->error(lang('goods_class_batch_edit_fail'));
            }

            $this->log(lang('ds_edit').lang('goods_class_index_class') . '[' . $_POST['gc_name'] . ']', 1);
            $this->success(lang('goods_class_batch_edit_ok'), url('Admin/Teachtype/index'));
        }
    }

    /**
     * 删除分类
     */
    public function type_class_del() {

        $model_class = model('Teachtype');
        $gc_id = intval(input('param.gc_id'));
        if ($gc_id > 0) {
            //删除分类
            $model_class->delTypeClassByGcIdString($gc_id);
            $this->log(lang('ds_del,goods_class_index_class') . '[ID:' . $gc_id . ']', 1);
            $this->success(lang('ds_common_del_succ'), url('Admin/Teachtype/index'));
        } else {
            $this->log(lang('ds_del,goods_class_index_class') . '[ID:' . $gc_id . ']', 0);
            $this->error(lang('ds_common_del_fail'), url('Admin/Teachtype/index'));
        }
    }

    /**
     * ajax操作
     */
    public function ajax() {
        $branch = input('param.branch');

        switch ($branch) {
            /**
             * 更新分类
             */
            case 'goods_class_name':
                $model_class = model('Teachtype');
                $class_array = $model_class->getGoodsClassInfoById(intval($_GET['id']));

                $condition['gc_name'] = trim($_GET['value']);
                $condition['gc_parent_id'] = $class_array['gc_parent_id'];
                $condition['gc_id'] = array('neq' => intval($_GET['id']));
                $class_list = $model_class->getGoodsClassList($condition);
                if (empty($class_list)) {
                    $where = array('gc_id' => intval($_GET['id']));
                    $update_array = array();
                    $update_array['gc_name'] = trim($_GET['value']);
                    $model_class->editGoodsClass($update_array, $where);
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = model('Teachtype');
                $condition['gc_name'] = trim(input('get.gc_name'));
                $condition['gc_parent_id'] = intval(input('get.gc_parent_id'));
                $condition['gc_id'] = array('neq', intval(input('get.gc_id')));
                $class_list = $model_class->getGoodsClassList($condition);
                if (empty($class_list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '管理',
                'url' => url('Admin/Teachtype/index')
            ),
        );
        if(session('admin_is_super') ==1 || in_array(1,$this->action )){
            if (request()->action() == 'type_class_add' || request()->action() == 'index') {
                $menu_array[] = array(
                    'name' => 'type_class_add',
                    'text' => '新增',
                    'url' => url('Admin/Teachtype/type_class_add')
                );
            }
        }
        if (request()->action() == 'type_class_edit') {
            $gc_id = trim($_GET['gc_id']);
            $menu_array[] = array(
                'name' => 'type_class_edit',
                'text' => '编辑',
                'url' => url('Admin/Teachtype/type_class_edit',array('gc_id'=>$gc_id))
            );
        }
        return $menu_array;
    }

}

?>
