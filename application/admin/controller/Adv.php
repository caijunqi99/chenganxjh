<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Adv extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/adv.lang.php');
    }

    /**
     *
     * 管理广告位
     */
    public function ap_manage() {
        $adv = model('adv');
        /**
         * 多选删除广告位
         */
        if (!request()->isPost()) {
            /**
             * 显示广告位管理界面
             */
            $condition = array();
            $orderby = '';
            $search_name = trim(input('get.search_name'));
            if ($search_name != '') {
                $condition['ap_name'] = $search_name;
            }
            $ap_list = $adv->getApList($condition, '10', $orderby);
            $adv_list = $adv->getList();
            $this->assign('ap_list', $ap_list);
            $this->assign('adv_list', $adv_list);
            $this->assign('page', $adv->page_info->render());
            $this->setAdminCurItem('ap_manage');
            return $this->fetch('ap_manage');
        }
    }

    /**
     *
     * 修改广告位
     */
    public function ap_edit() {
        $ap_id = intval(input('param.ap_id'));

        if (!request()->isPost()) {
            $adv = Model('adv');
            $condition['ap_id'] = $ap_id;
            $ap = $adv->getOneAp($condition);
            $this->assign('ref_url', getReferer());
            $this->assign('ap', $ap);
            $this->setAdminCurItem('ap_edit');
            return $this->fetch('ap_form');
        } else {
            $adv = Model('adv');

            $param['ap_id'] = $ap_id;
            $param['ap_name'] = trim(input('post.ap_name'));
            $param['ap_intro'] = trim(input('post.ap_intro'));
            $param['ap_width'] = intval(trim(input('post.ap_width')));
            $param['ap_height'] = intval(trim(input('post.ap_height')));
            if (input('post.is_use') != '') {
                $param['is_use'] = intval(input('post.is_use'));
            }


            //验证数据  BEGIN
            $rule = [
                ['ap_name', 'require', lang('ap_can_not_null')],
                ['ap_width', 'require', lang('ap_width_must_num')],
                ['ap_height', 'require', lang('ap_height_must_num')],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($param);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END
            $result = $adv->ap_update($param);

            if ($result) {
                $this->log(lang('ap_change_succ') . '[' . input('post.ap_name') . ']', null);
                $this->success(lang('ap_change_succ'), input('post.ref_url'));
            } else {
                $this->error(lang('ap_change_fail'));
            }
        }
    }

    /**
     *
     * 新增广告位
     */
    public function ap_add() {
        if (!request()->isPost()) {
            $this->setAdminCurItem('ap_add');
            return $this->fetch('ap_form');
        } else {
            $adv = Model('adv');

            $insert_array['ap_name'] = trim(input('post.ap_name'));
            $insert_array['ap_intro'] = trim(input('post.ap_intro'));
            $insert_array['is_use'] = intval(input('post.is_use'));
            $insert_array['ap_width'] = intval(input('post.ap_width'));
            $insert_array['ap_height'] = intval(input('post.ap_height'));

            //验证数据  BEGIN
            $rule = [
                ['ap_name', 'require', lang('ap_can_not_null')],
                ['ap_width', 'require', lang('ap_width_must_num')],
                ['ap_height', 'require', lang('ap_height_must_num')],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($insert_array);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END

            $result = $adv->ap_add($insert_array);

            if ($result) {
                $this->log(lang('ap_add_succ') . '[' . input('post.ap_name') . ']', null);
                $this->success(lang('ap_add_succ'), url('Admin/Adv/ap_manage'));
            } else {
                $this->error(lang('ap_add_fail'));
            }
        }
    }

    /**
     *
     * 删除广告位
     */
    public function ap_del() {
        $adv = Model('adv');
        /**
         * 删除一个广告
         */
        $ap_id = intval(input('param.ap_id'));
        $result = $adv->ap_del($ap_id);

        if (!$result) {
            $this->error(lang('ap_del_fail'));
        } else {
            $this->log(lang('ap_del_succ') . '[' . $ap_id . ']', null);
            $this->success(lang('ap_del_succ'));
        }
    }

    /**
     *
     * 广告管理
     */
    public function adv() {
        $adv = Model('adv');
        $ap_id = intval(input('param.ap_id'));
        if (!request()->isPost()) {
            $condition = array();
            $condition['is_allow'] = '1';
            if ($ap_id) {
                $condition['ap_id'] = $ap_id;
            }
            $adv_info = $adv->getList($condition, 20, '', '');
            $this->assign('adv_info', $adv_info);
            $ap_list = $adv->getApList();
            $this->assign('ap_list', $ap_list);
            if ($ap_id) {
                $ap = db('advposition')->where('ap_id', $ap_id)->find();
                $this->assign('ap_name', $ap['ap_name']);
            } else {
                $this->assign('ap_name', '');
            }

            $this->assign('page', $adv->page_info->render());
            $this->setAdminCurItem('adv');
            return $this->fetch('adv_index');
        }
    }

    /**
     * 管理员添加广告
     */
    public function adv_add() {
        if (!request()->isPost()) {
            $adv = Model('adv');
            $ap_list = $adv->getApList();
            $this->assign('ap_list', $ap_list);
            $adv = array(
                'adv_start_date' => time(),
                'adv_end_date' => time() + 24 * 3600 * 365,
            );
            $this->assign('adv', $adv);
            $this->setAdminCurItem('adv_add');
            return $this->fetch('adv_form');
        } else {
            $adv = Model('adv');

            $insert_array['adv_title'] = trim($_POST['adv_name']);
            $insert_array['ap_id'] = intval($_POST['ap_id']);
            $insert_array['adv_start_date'] = $this->getunixtime($_POST['adv_start_date']);
            $insert_array['adv_end_date'] = $this->getunixtime($_POST['adv_end_date']);
            $insert_array['adv_link'] = input('post.adv_link');
            $insert_array['is_allow'] = '1';


            //上传文件保存路径
            $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_ADV;
            if (!empty($_FILES['adv_code']['name'])) {
                $file = request()->file('adv_code');
                $info = $file->rule('uniqid')->validate(['ext' => 'jpg,png,gif'])->move($upload_file);
                if ($info) {
                    $insert_array['adv_code'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }

            //验证数据  BEGIN
            $rule = [
                ['adv_title', 'require', lang('adv_can_not_null')],
                ['ap_id', 'require', lang('must_select_ap')],
                ['adv_start_date', 'require', lang('must_select_start_time')],
                ['adv_end_date', 'require', lang('must_select_end_time')],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($insert_array);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END
            //广告信息入库
            $result = $adv->adv_add($insert_array);
            //更新相应广告位所拥有的广告数量
            $ap_list = db('advposition')->where('ap_id', intval(input('post.ap_id')))->find();
            $adv_num = $ap_list['adv_num'];
            $param['ap_id'] = intval(input('post.ap_id'));
            $param['adv_num'] = $adv_num + 1;
            $result2 = $adv->ap_update($param);

            if ($result && $result2) {
                $this->log(lang('adv_add_succ') . '[' . input('post.adv_name') . ']', null);
                $this->success(lang('adv_add_succ'), url('Adv/adv', ['ap_id' => input('post.ap_id')]));
            } else {
                $this->error(lang('adv_add_fail'));
            }
        }
    }

    /**
     *
     * 修改广告
     */
    public function adv_edit() {
        $adv_id = intval(input('param.adv_id'));
        if (!request()->isPost()) {
            $adv_model = Model('adv');
            //获取广告列表
            $ap_list = $adv_model->getApList();
            $this->assign('ap_list', $ap_list);
            //获取指定广告
            $condition['adv_id'] = $adv_id;
            $adv = $adv_model->getOneAdv($condition);
            $this->assign('adv', $adv);
            $this->assign('ref_url', getReferer());
            $this->setAdminCurItem('adv_edit');
            return $this->fetch('adv_form');
        } else {
            $adv_model = Model('adv');


            $param['adv_id'] = $adv_id;
            $param['adv_title'] = trim($_POST['adv_name']);
            $param['adv_start_date'] = $this->getunixtime(trim($_POST['adv_start_date']));
            $param['adv_end_date'] = $this->getunixtime(trim($_POST['adv_end_date']));
            $param['adv_link'] = input('post.adv_link');

            if (!empty($_FILES['adv_code']['name'])) {
                //上传文件保存路径
                $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_ADV;
                $file = request()->file('adv_code');
                $info = $file->rule('uniqid')->validate(['ext' => 'jpg,png,gif'])->move($upload_file);
                if ($info) {
                    //还需删除原来图片
                    $adv_code_ori = input('param.adv_code_ori');
                    if($adv_code_ori){
                        @unlink($upload_file. DS .$adv_code_ori);
                    }
                    $param['adv_code'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }

            //验证数据  BEGIN
            $rule = [
                ['adv_title', 'require', lang('adv_can_not_null')],
                ['adv_start_date', 'require', lang('must_select_start_time')],
                ['adv_end_date', 'require', lang('must_select_end_time')],
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($param);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END

            $result = $adv_model->adv_update($param);

            if ($result) {
                $this->log(lang('adv_change_succ') . '[' . input('post.ap_name') . ']', null);
                $this->success(lang('adv_change_succ'), input('post.ref_url'));
            } else {
                $this->error(lang('adv_change_fail'));
            }
        }
    }

    /**
     *
     * 删除广告
     */
    public function adv_del() {
        $adv_model = Model('adv');
        /**
         * 删除一个广告
         */
        $adv_id = intval(input('param.adv_id'));
        $result = $adv_model->adv_del($adv_id);

        if (!$result) {
            $this->error(lang('adv_del_fail'));
        } else {
            $this->log(lang('adv_del_succ') . '[' . $adv_id . ']', null);
            $this->success(lang('adv_del_succ'));
        }
    }

    /**
     *
     * 获取UNIX时间戳
     */
    public function getunixtime($time) {
        $array = explode("-", $time);
        $unix_time = mktime(0, 0, 0, $array[1], $array[2], $array[0]);
        return $unix_time;
    }

    /**
     *
     * ajaxOp
     */
    public function ajax() {
        switch ($_GET['branch']) {
            case 'is_use':
                $adv_model = Model('adv');
                $param[trim($_GET['column'])] = intval($_GET['value']);
                $param['ap_id'] = intval($_GET['id']);
                $adv_model->ap_update($param);
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
                'name' => 'ap_manage',
                'text' => lang('ap_manage'),
                'url' => url('Admin/Adv/ap_manage')
            ),
        );
        $menu_array[] = array(
            'name' => 'ap_add',
            'text' => lang('ap_add'),
            'url' => url('Admin/Adv/ap_add')
        );

        $menu_array[] = array(
            'name' => 'adv',
            'text' => lang('adv_manage'),
            'url' => url('Admin/Adv/adv')
        );
        $menu_array[] = array(
            'name' => 'adv_add',
            'text' => lang('adv_add'),
            'url' => url('Admin/Adv/adv_add', ['ap_id' => input('param.ap_id')])
        );
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Article/edit')
            );
        }
        return $menu_array;
    }

}

?>
