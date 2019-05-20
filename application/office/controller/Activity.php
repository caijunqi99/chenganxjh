<?php

namespace app\office\controller;

use think\Lang;
use think\Validate;

class Activity extends AdminControl {

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'office/lang/zh-cn/activity.lang.php');
    }

    /**
     * 活动列表/删除活动
     */
    public function index() {
        $activity = Model('activity');
        //条件
        $condition_arr = array();
        $condition_arr['activity_type'] = '1'; //只显示商品活动
        //状态
        if ((input('param.searchstate'))) {
            $state = intval(input('param.searchstate')) - 1;
            $condition_arr['activity_state'] = "$state";
        }
        //标题
        if ((input('param.searchtitle'))) {
            $condition_arr['activity_title'] = input('param.searchtitle');
        }
        //有效期范围
        if ((input('param.searchstartdate')) && (input('param.searchenddate'))) {
            $condition_arr['activity_daterange']['startdate'] = strtotime(input('param.searchstartdate'));
            $condition_arr['activity_daterange']['enddate'] = strtotime(input('param.searchenddate'));
            if ($condition_arr['activity_daterange']['enddate'] > 0) {
                $condition_arr['activity_daterange']['enddate'] += 86400;
            }
        }
        $condition_arr['order'] = 'activity_sort asc';
        //活动列表
        $list = $activity->getList($condition_arr, 10);
        //输出
        $this->assign('show_page', $activity->page_info->render());
        $this->assign('list', $list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 新建活动/保存新建活动
     */
    public function add() {
        if (request()->isPost()) {
            //提交表单
            $obj_validate = new Validate();
            $data = [
                'activity_title' => $_POST["activity_title"],
                'activity_start_date' => $_POST["activity_start_date"],
                'activity_end_date' => $_POST["activity_end_date"],
                'activity_style' => $_POST["activity_style"],
                'activity_type' => $_POST['activity_type'],
                'activity_banner' => $_FILES['activity_banner']['name'],
                'activity_sort' => $_POST['activity_sort']
            ];
            $rule = [
                ['activity_title', 'require', lang('activity_new_title_null')],
                ['activity_start_date', 'require', lang('activity_new_startdate_null')],
                ['activity_end_date', "require|after:{$_POST['activity_start_date']}", lang('activity_new_enddate_null')],
                ['activity_style', 'require', lang('activity_new_style_null')],
                ['activity_type', 'require', lang('activity_new_type_null')],
                ['activity_banner', 'require', lang('activity_new_banner_null')],
                ['activity_sort', 'require', lang('activity_new_sort_error')]
            ];
            $error = $obj_validate->check($data, $rule);
            if (!$error) {
                $this->error($obj_validate->getError());
            }
            $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_ACTIVITY;
            $file = request()->file('activity_banner');
            $info = $file->validate(['ext' => 'jpg,png,gif'])->move($upload_file);
            if ($info) {
                $file_name = $info->getSaveName();
            }

            //保存
            $input = array();
            $input['activity_title'] = trim($_POST['activity_title']);
            //$input['activity_type']		= trim($_POST['activity_type']);
            $input['activity_type'] = '1';
            $input['activity_banner'] = $file_name;
            $input['activity_style'] = trim($_POST['activity_style']);
            $input['activity_desc'] = trim($_POST['activity_desc']);
            $input['activity_sort'] = intval(trim($_POST['activity_sort']));
            $input['activity_start_date'] = strtotime(trim($_POST['activity_start_date']));
            $input['activity_end_date'] = strtotime(trim($_POST['activity_end_date']));
            $input['activity_state'] = intval($_POST['activity_state']);
            $activity = Model('activity');
            $result = $activity->add($input);
            if ($result) {
                $this->log(lang('ds_add').lang('activity_index') . '[' . $_POST['activity_title'] . ']', null);
                $this->error(lang('ds_common_op_succ'), 'activity/index');
            } else {
                //添加失败则删除刚刚上传的图片,节省空间资源
                @unlink($upload_file . DS . $file_name);
                $this->error(lang('ds_common_op_fail'));
            }
        } else {
            $this->setAdminCurItem('add');
            return $this->fetch();
        }
    }

    /**
     * 异步修改
     */
    public function ajax() {
        if (in_array(input('param.branch'), array('activity_title', 'activity_sort'))) {
            $activity = Model('activity');
            $update_array = array();
            switch (input('param.branch')) {
                /**
                 * 活动主题
                 */
                case 'activity_title':
                    if (trim(input('param.value')) == '')
                        exit;
                    break;
                /**
                 * 排序
                 */
                case 'activity_sort':
                    if (preg_match('/^\d+$/', trim(input('param.value'))) <= 0 or intval(trim(input('param.value'))) < 0 or intval(trim(input('param.value'))) > 255)
                        exit;
                    break;
                default:
                    exit;
            }
            $update_array[input('param.column')] = trim(input('param.value'));
            if ($activity->updates($update_array, intval(input('param.id'))))
                echo 'true';
        }
        elseif (in_array(input('param.branch'), array('activity_detail_sort'))) {
            $activity_detail = Model('activitydetail');
            $update_array = array();
            switch (input('param.branch')) {
                /**
                 * 排序
                 */
                case 'activity_detail_sort':
                    if (preg_match('/^\d+$/', trim(input('param.value'))) <= 0 or intval(trim(input('param.value'))) < 0 or intval(trim(input('param.value'))) > 255)
                        exit;
                    break;
                default:
                    exit;
            }
            $update_array[input('param.column')] = trim(input('param.value'));
            if ($activity_detail->updates($update_array, intval(input('param.id'))))
                echo 'true';
        }
    }

    /**
     * 删除活动
     */
    public function del() {
        $id = '';
        if (!(input('param.activity_id'))) {
            $this->error(lang('activity_del_choose_activity'));
        }
        if (is_array($_POST['activity_id'])) {
            try {
                //删除数据先删除横幅图片，节省空间资源
                foreach ($_POST['activity_id'] as $v) {
                    $this->delBanner(intval($v));
                }
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $id = "'" . implode("','", $_POST['activity_id']) . "'";
        } else {
            //删除数据先删除横幅图片，节省空间资源
            $this->delBanner(intval(input('param.activity_id')));
            $id = intval(input('param.activity_id'));
        }
        $activity = Model('activity');
        $activity_detail = Model('activitydetail');
        //获取可以删除的数据
        $condition_arr = array();
        $condition_arr['activity_state'] = '0'; //已关闭
        $condition_arr['activity_enddate_greater_or'] = time(); //过期
        $condition_arr['activity_id_in'] = $id;
        $activity_list = $activity->getList($condition_arr);
        if (empty($activity_list)) {//没有符合条件的活动信息直接返回成功信息
            $this->error(lang('ds_common_del_succ'));
        }
        $id_arr = array();
        foreach ($activity_list as $v) {
            $id_arr[] = $v['activity_id'];
        }
        $id_new = "'" . implode("','", $id_arr) . "'";
        //只有关闭或者过期的活动，能删除
        if ($activity_detail->del($id_new)) {
            if ($activity->del($id_new)) {
                $this->log(lang('ds_del').lang('activity_index') . '[ID:' . $id . ']', null);
                $this->error(lang('ds_common_del_succ'));
            }
        }
        $this->error(lang('activity_del_fail'));
    }

    /**
     * 编辑活动/保存编辑活动
     */
    public function edit() {
        if (!request()->isPost()) {
            if (!(input('param.activity_id'))) {
                $this->error(lang('miss_argument'));
            }
            $activity = Model('activity');
            $row = $activity->getOneById(intval(input('param.activity_id')));
            $this->assign('activity', $row);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        }
        //提交表单
        $obj_validate = new Validate();
        $data = [
            'activity_title' => $_POST["activity_title"],
            'activity_start_date' => $_POST["activity_start_date"],
            'activity_end_date' => $_POST["activity_end_date"],
            'activity_style' => $_POST["activity_style"],
            'activity_type' => $_POST['activity_type'],
            'activity_sort' => $_POST['activity_sort']
        ];
        $rule = [
            ['activity_title', 'require', lang('activity_new_title_null')],
            ['activity_start_date', 'require', lang('activity_new_startdate_null')],
            ['activity_end_date', "require|after:{$_POST['activity_start_date']}", lang('activity_new_enddate_null')],
            ['activity_style', 'require', lang('activity_new_style_null')],
            ['activity_type', 'require', lang('activity_new_type_null')],
            ['activity_sort', 'require', lang('activity_new_sort_error')]
        ];
        $error = $obj_validate->check($data, $rule);
        if (!$error) {
            $this->error($obj_validate->getError());
        }
        //构造更新内容
        $input = array();
        if ($_FILES['activity_banner']['name'] != '') {
            $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_ACTIVITY;
            $file = request()->file('activity_banner');
            $info = $file->validate(['ext' => 'jpg,png,gif'])->move($upload_file);
            if ($info) {
                $file_name = $info->getSaveName();
                $input['activity_banner'] = $file_name;
            }
        }
        $input['activity_title'] = trim($_POST['activity_title']);
        $input['activity_type'] = trim($_POST['activity_type']);
        $input['activity_style'] = trim($_POST['activity_style']);
        $input['activity_desc'] = trim($_POST['activity_desc']);
        $input['activity_sort'] = intval(trim($_POST['activity_sort']));
        $input['activity_start_date'] = strtotime(trim($_POST['activity_start_date']));
        $input['activity_end_date'] = strtotime(trim($_POST['activity_end_date']));
        $input['activity_state'] = intval($_POST['activity_state']);

        $activity = Model('activity');
        $row = $activity->getOneById(intval($_POST['activity_id']));
        $result = $activity->updates($input, intval($_POST['activity_id']));
        if ($result) {
            if ($_FILES['activity_banner']['name'] != '') {
                @unlink($upload_file . DS . $row['activity_banner']);
            }
            $this->log(lang('ds_edit') . lang('activity_index') . '[ID:' . $_POST['activity_id'] . ']', null);
            $this->success(lang('ds_common_save_succ'), 'activity/index');
        } else {
            if ($_FILES['activity_banner']['name'] != '') {
                @unlink($upload_file . $file_name);
            }
            $this->error(lang('ds_common_save_fail'));
        }
    }

    /**
     * 活动细节列表
     */
    public function detail() {
        $activity_id = intval(input('param.id'));
        if ($activity_id <= 0) {
            $this->error(lang('miss_argument'));
        }
        //条件
        $condition_arr = array();
        $condition_arr['activity_id'] = $activity_id;
        //审核状态
        if ((input('param.searchstate'))) {
            $state = intval(input('param.searchstate')) - 1;
            $condition_arr['activity_detail_state'] = "$state";
        }
        //店铺名称
        if ((input('param.searchstore'))) {
            $condition_arr['store_name'] = input('param.searchstore');
        }
        //商品名称
        if ((input('param.searchgoods'))) {
            $condition_arr['item_name'] = input('param.searchgoods');
        }
        $condition_arr['order'] = 'a.activity_detail_state asc,a.activity_detail_sort asc';

        $activitydetail_model = Model('activitydetail');
        $list = $activitydetail_model->getList($condition_arr, 10);
        //输出到模板
        $this->assign('show_page', $activitydetail_model->page_info->render());
        $this->assign('list', $list);
        $this->setAdminCurItem('detail');
        return $this->fetch();
    }

    /**
     * 活动内容处理
     */
    public function deal() {
        if (empty($_POST['activity_detail_id'])) {
            $this->error(lang('activity_detail_del_choose_detail'));
        }
        //获取id
        
        $id = '';
        if (is_array($_POST['activity_detail_id'])) {
            $id = $_POST['activity_detail_id'];
        } else {
            $id = intval(input('param.activity_detail_id'));
        }
        $condition['activity_detail_id'] = array('in', $id);
        //创建活动内容对象
        $activity_detail = Model('activitydetail');
        if ($activity_detail->where($condition)->update(array('activity_detail_state' => intval(input('param.state'))))) {
            $this->log(lang('ds_edit').lang('activity_index') . '[ID:' . $id . ']', null);
            $this->success(lang('ds_common_op_succ'));
        } else {
            $this->error(lang('ds_common_op_fail'));
        }
    }

    /**
     * 删除活动内容
     */
    public function del_detail() {
        if (empty($_REQUEST['activity_detail_id'])) {
            $this->success(lang('activity_detail_del_choose_detail'));
        }
        $id = '';
        if (is_array($_POST['activity_detail_id'])) {
            $id = "'" . implode("','", $_POST['activity_detail_id']) . "'";
        } else {
            $id = "'" . intval(input('param.activity_detail_id')) . "'";
        }
        $activity_detail = Model('activitydetail');
        //条件
        $condition_arr = array();
        $condition_arr['activity_detail_id_in'] = $id;
        $condition_arr['activity_detail_state_in'] = "'0','2'"; //未审核和已拒绝
        if ($activity_detail->delList($condition_arr)) {
            $this->log(lang('ds_del') . lang('activity_index_content') . '[ID:' . $id . ']', null);
            $this->success(lang('ds_common_del_succ'));
        } else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * 根据活动编号删除横幅图片
     *
     * @param int $id
     */
    private function delBanner($id) {
        $activity = Model('activity');
        $row = $activity->getOneById($id);
        //删除图片文件
        @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ACTIVITY . DS . $row['activity_banner']);
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '管理', 'url' => url('Activity/index')
            ), array(
                'name' => 'add', 'text' => '新增', 'url' => url('Activity/add')
            ),
        );
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit', 'text' => '编辑', 'url' => url('Activity/edit')
            );
        }
        if (request()->action() == 'detail') {
            $menu_array[] = array(
                'name' => 'detail', 'text' => '处理申请', 'url' => url('Activity/detail')
            );
        }
        return $menu_array;
    }

}