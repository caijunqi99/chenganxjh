<?php

namespace app\admin\controller;


use think\Lang;
use think\Validate;

class Inform extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/zh-cn/inform.lang.php');
    }

    /*
        * 未处理的举报列表
        */
    public function inform_list()
    {

        $this->get_inform_list(1,  'inform_list');
        return $this->fetch();
    }

    /*
     * 已处理的举报列表
     */
    public function inform_handled_list()
    {

        $this->get_inform_list(2, 'inform_handled_list');
        return $this->fetch();
    }


    /*
     * 获取举报列表
     */
    private function get_inform_list($type, $op)
    {

        //获得举报列表
        $model_inform = Model('inform');

        //搜索条件
        $condition = array();
        $condition['inform_goods_name'] = trim(input('param.input_inform_goods_name'));
        $condition['inform_member_name'] = trim(input('param.input_inform_member_name'));
        $condition['inform_type'] = trim(input('param.input_inform_type'));
        $condition['inform_subject'] = trim(input('param.input_inform_subject'));
        $condition['inform_datetime_start'] = strtotime(input('param.input_inform_datetime_start'));
        $condition['inform_datetime_end'] = strtotime(input('param.input_inform_datetime_end'));
        if ($type === 1) {
            $condition['order'] = 'inform_id asc';
        } else {
            $condition['order'] = 'inform_id desc';
        }
        $condition['inform_state'] = $type;
        $inform_list = $model_inform->getInform($condition, 10);

        $this->setAdminCurItem($op);
        $this->assign('list', $inform_list);
        $this->assign('show_page', $model_inform->page_info->render());
    }


    /*
     * 举报类型列表
     */
    public function inform_subject_type_list()
    {

        //获得有效举报类型列表
        $model_inform_subject_type = Model('informsubjecttype');
        $inform_type_list = $model_inform_subject_type->getActiveInformSubjectType(10);

        $this->setAdminCurItem('inform_subject_type_list');
        $this->assign('list', $inform_type_list);
        $this->assign('show_page', $model_inform_subject_type->page_info->render());
        return $this->fetch();
    }


    /*
     * 举报主题列表
     */
    public function inform_subject_list()
    {

        //获得举报主题列表
        $model_inform_subject = Model('informsubject');

        //搜索条件
        $condition = array();
        $condition['order'] = 'inform_subject_id asc';
        $condition['inform_subject_type_id'] = trim(input('param.inform_subject_type_id'));
        $condition['inform_subject_state'] = 1;
        $inform_subject_list = $model_inform_subject->getInformSubject($condition, 10);

        //获取有效举报类型
        $model_inform_subject_type = Model('informsubjecttype');
        $type_list = $model_inform_subject_type->getActiveInformSubjectType();

        $this->setAdminCurItem('inform_subject_list');
        $this->assign('list', $inform_subject_list);
        $this->assign('type_list', $type_list);
        $this->assign('show_page', $model_inform_subject->page_info->render());
        return $this->fetch();
    }

    /*
     * 添加举报类型页面
     */
    public function inform_subject_type_add()
    {
        $this->setAdminCurItem('inform_subject_type_add');
        return $this->fetch();

    }

    /*
     * 保存添加的举报类型
     */
    public function inform_subject_type_save()
    {

        //获取提交的内容
        $input['inform_type_name'] = trim($_POST['inform_type_name']);
        $input['inform_type_desc'] = trim($_POST['inform_type_desc']);

        //验证提交的内容
        $obj_validate = new Validate();
        $data=[
           'inform_type_name'=> $input['inform_type_name'],
           'inform_type_desc'=>$input['inform_type_desc'],
        ];
        $rule=[
            ['inform_type_name','require|max:50|min:1',lang('inform_type_null')],
            ['inform_type_desc','require|max:50|min:1',lang('inform_type_desc_null')]
        ];
       $error=$obj_validate->check($data,$rule);

        if (!$error) {
            $this->error($obj_validate->getError());
        } else {
            //验证成功保存
            $input['inform_type_state'] = 1;
            $model_inform_subject_type = Model('informsubjecttype');
            $model_inform_subject_type->saveInformSubjectType($input);
            $this->log(lang('ds_add').lang('inform_type') . '[' . $_POST['inform_type_name'] . ']', 1);
            $this->success(lang('ds_common_save_succ'), 'inform/inform_subject_type_list');
        }
    }

    /*
     * 删除举报类型,伪删除只是修改标记
     */
    public function inform_subject_type_drop()
    {

        $inform_type_id = trim($_POST['inform_type_id']);
        $inform_type_id = "'" . implode("','", explode(',', $inform_type_id)) . "'";
        if (empty($inform_type_id)) {
            $this->error(lang('param_error'));
        }

        //删除分类
        $model_inform_subject_type = Model('informsubjecttype');
        $update_array = array();
        $update_array['inform_type_state'] = 2;
        $where_array = array();
        $where_array['in_inform_type_id'] = $inform_type_id;
        $model_inform_subject_type->updateInformSubjectType($update_array, $where_array);

        //删除分类下边的主题
        $model_inform_subject = Model('informsubject');
        $update_subject_array = array();
        $update_subject_array['inform_subject_state'] = 2;
        $where_subject_array = array();
        $where_subject_array['in_inform_subject_type_id'] = $inform_type_id;
        $model_inform_subject->updateInformSubject($update_subject_array, $where_subject_array);
        $this->log(lang('ds_del').lang('inform_type') . '[ID:' . $_POST['inform_type_id'] . ']', 1);
        $this->error(lang('ds_common_del_succ'), 'inform/inform_subject_type_list');
    }


    /*
     * 添加举报主题页面
     */
    public function inform_subject_add()
    {

        //获得可用举报类型列表
        $model_inform_subject_type = Model('informsubjecttype');
        $inform_type_list = $model_inform_subject_type->getActiveInformSubjectType();

        if (empty($inform_type_list)) {
            $this->error(lang('inform_type_error'));
        }
        $this->setAdminCurItem('inform_subject_add');
        $this->assign('list', $inform_type_list);
        return $this->fetch('');

    }

    /*
     * 保存添加的举报主题
     */
    public function inform_subject_save()
    {
        //获取提交的内容
        list($input['inform_subject_type_id'], $input['inform_subject_type_name']) = explode(',', trim($_POST['inform_subject_type']));
        $input['inform_subject_content'] = trim($_POST['inform_subject_content']);

        //验证提交的内容
        $obj_validate = new Validate();
        $data=[
            'inform_subject_type_name'=>$input['inform_subject_type_name'],
            'inform_subject_content'=>$input['inform_subject_content'],
            'inform_subject_type_id'=>$input['inform_subject_type_id']
        ];
        $rule=[
            ['inform_subject_type_name','require|min:1|max:50',lang('inform_subject_null')],
            ['inform_subject_content','require|min:1|max:50',lang('inform_content_null')],
            ['inform_subject_type_id','require|min:1|max:50',lang('param_error')]
        ];

        $error = $obj_validate->check($data,$rule);

        if (!$error) {
            $this->error($obj_validate->getError());
        } else {
            //验证成功保存
            $input['inform_subject_state'] = 1;
            $model_inform_subject = Model('informsubject');
            $model_inform_subject->saveInformSubject($input);
            $this->log('添加'.lang('inform_subject') . '[' . $input['inform_subject_type_name'] . ']', 1);
            $this->success(lang('ds_common_save_succ'), 'inform/inform_subject_list');
        }
    }

    /*
     * 删除举报主题,伪删除只是修改标记
     */
    public function inform_subject_drop()
    {

        $inform_subject_id = trim($_POST['inform_subject_id']);
        if (empty($inform_subject_id)) {
            $this->error(lang('param_error'));
        }
        $model_inform_subject = Model('informsubject');
        $update_array = array();
        $update_array['inform_subject_state'] = 2;
        $where_array = array();
        $where_array['in_inform_subject_id'] = "'" . implode("','", explode(',', $inform_subject_id)) . "'";
        $model_inform_subject->updateInformSubject($update_array, $where_array);
        $this->log(lang('ds_del').lang('inform_subject') . '[' . $_POST['inform_subject_id'] . ']', 1);
        $this->success(lang('ds_common_del_succ'), 'inform/inform_subject_list');
    }

    /*
     * 显示处理举报
     */
    public function show_handle_page()
    {
        $this->setAdminCurItem('inform_list');
        $inform_id = intval(input('param.inform_id'));
        $inform_goods_name = urldecode(input('param.inform_goods_name'));

        $this->assign('inform_id', $inform_id);
        $this->assign('inform_goods_name', $inform_goods_name);
        return $this->fetch('inform_handle');
    }

    /*
     * 处理举报
     */
    public function inform_handle()
    {

        $inform_id = intval($_POST['inform_id']);
        $inform_handle_type = intval($_POST['inform_handle_type']);
        $inform_handle_message = trim($_POST['inform_handle_message']);

        if (empty($inform_id) || empty($inform_handle_type)) {
            $this->error(lang('param_error'));
        }

        //验证输入的数据
        $obj_validate = new Validate();
        $data= [
                "inform_handle_message" => $inform_handle_message,
        ];
        $rule=[
            ['inform_handle_message','require|max:100|min:1',lang('inform_handle_message_null')]
        ];
        $error = $obj_validate->check($data,$rule);
        if (!$error) {
            $this->error($obj_validate->getError());
        }

        $model_inform = Model('inform');
        $inform_info = $model_inform->getoneInform($inform_id);
        if (empty($inform_info) || intval($inform_info['inform_state']) === 2) {
            $this->error(lang('param_error'));
        }

        $update_array = array();
        $where_array = array();

        //根据选择处理
        switch ($inform_handle_type) {

            case 1:
                $where_array['inform_id'] = $inform_id;
                break;
            case 2:
                //恶意举报，清理所有该用户的举报，设置该用户禁止举报
                $where_array['inform_member_id'] = $inform_info['inform_member_id'];
                $this->denyMemberInform($inform_info['inform_member_id']);
                break;
            case 3:
                //有效举报，商品禁售
                $where_array['inform_id'] = $inform_id;
                $this->denyGoods($inform_info['inform_goods_id']);
                break;
            default:
                $this->error(lang('param_error'));

        }

        $update_array['inform_state'] = 2;
        $update_array['inform_handle_type'] = $inform_handle_type;
        $update_array['inform_handle_message'] = $inform_handle_message;
        $update_array['inform_handle_datetime'] = time();
        $admin_info = $this->getAdminInfo();
        $update_array['inform_handle_member_id'] = $admin_info['admin_id'];
        $where_array['inform_state'] = 1;

        if ($model_inform->updateInform($update_array, $where_array)) {
            $this->log(lang('inform_text_handle,inform') . '[ID:' . $inform_id . ']', 1);
            $this->success(lang('ds_common_op_succ'), 'inform/inform_list');
        } else {
            $this->error(lang('ds_common_op_fail'));
        }
    }

    /*
     * 禁止该用户举报
     */
    private function denyMemberInform($member_id)
    {

        $model_member = Model('member');
        $param = array();
        $param['inform_allow'] = 2;
        return $model_member->editMember(array('member_id' => $member_id), $param);
    }

    /*
     * 禁止商品销售
     */
    private function denyGoods($goods_id)
    {
        //修改商品状态
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        if (empty($goods_info)) {
            return true;
        }
        return Model('goods')->editProducesLockUp(array('goods_stateremark' => '商品被举报，平台禁售'), array('goods_commonid' => $goods_info['goods_commonid']));

    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'inform_list',
                'text' => lang('inform_state_unhandle'),
                'url' => url('inform/inform_list')
            ),
            array(
                'name' => 'inform_handled_list',
                'text' => lang('inform_state_handled'),
                'url' => url('inform/inform_handled_list')
            ),
            array(
                'name' => 'inform_subject_type_list',
                'text' => lang('inform_type'),
                'url' => url('inform/inform_subject_type_list')
            ),
            array(
                'name' => 'inform_subject_type_add',
                'text' => lang('inform_type_add'),
                'url' => url('inform/inform_subject_type_add')
            ),
            array(
                'name' => 'inform_subject_list',
                'text' => lang('inform_subject'),
                'url' => url('inform/inform_subject_list')
            ),
            array(
                'name' => 'inform_subject_add',
                'text' => lang('inform_subject_add'),
                'url' => url('inform/inform_subject_add')
            ),
        );
       return $menu_array;
    }
}