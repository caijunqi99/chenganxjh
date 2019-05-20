<?php
/**
 * 平台客服
 * Date: 2017/7/17
 * Time: 11:58
 */

namespace app\office\controller;


use think\Validate;

class Mallconsult extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 咨询管理
     */
    public function index()
    {
        $condition = array();
        if (request()->isPost()) {
            $member_name = trim(input('param.member_name'));
            if ($member_name != '') {
                $condition['member_name'] = array('like', '%' . $member_name . '%');
                $this->assign('member_name', $member_name);
            }
            $mct_id = intval(input('param.mctid'));
            if ($mct_id > 0) {
                $condition['mct_id'] = $mct_id;
                $this->assign('mctid', $mct_id);
            }
        }
        $model_mallconsult = Model('mallconsult');
        $consult_list = $model_mallconsult->getMallConsultList($condition, '*', 10);
        $this->assign('show_page', $model_mallconsult->page_info->render());
        $this->assign('consult_list', $consult_list);


        // 咨询类型列表
        $type_list = Model('mallconsulttype')->getMallConsultTypeList(array(), 'mct_id,mct_name', 'mct_id');
        $this->assign('type_list', $type_list);

        // 回复状态
        $state = array('0' => '未回复', '1' => '已回复');
        $this->assign('state', $state);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 回复咨询
     */
    public function consult_reply()
    {
        $model_mallconsult = Model('mallconsult');
        if (request()->isPost()) {
            $mc_id = intval($_POST['mc_id']);
            $reply_content = trim($_POST['reply_content']);
            if ($mc_id <= 0 || $reply_content == '') {
                $this->error(lang('param_error'));
            }
            $update['is_reply'] = 1;
            $update['mc_reply'] = $reply_content;
            $update['mc_reply_time'] = TIMESTAMP;
            $update['admin_id'] = $this->admin_info['admin_id'];
            $update['admin_name'] = $this->admin_info['admin_name'];
            $result = $model_mallconsult->editMallConsult(array('mc_id' => $mc_id), $update);
            if ($result) {
                $consult_info = $model_mallconsult->getMallConsultInfo(array('mc_id' => $mc_id));
                // 发送用户消息
                $param = array();
                $param['code'] = 'consult_mall_reply';
                $param['member_id'] = $consult_info['member_id'];
                $param['param'] = array(
                    'consult_url' => url('office/Membermallconsult/mallconsult_info',['id'=>$mc_id])
                );
                \mall\queue\QueueClient::push('sendMemberMsg', $param);

                $this->success('回复成功', url('mallconsult/index'));
            }
            else {
                $this->error('回复失败');
            }
        }
        $id = intval(input('param.id'));
        if ($id <= 0) {
            $this->error(lang('param_error'));
        }

        $consult_info = $model_mallconsult->getMallConsultDetail($id);
        $this->assign('consult_info', $consult_info);
        return $this->fetch();
    }

    /**
     * 删除平台客服咨询
     */
    public function del_consult()
    {
        $id = input('param.id');
        if ($id <= 0) {
            $this->error(lang('ds_common_del_fail'));
        }
        $result = Model('mallconsult')->delMallConsult(array('mc_id' => $id));
        if ($result) {
            $this->log('删除平台客服咨询' . '[ID:' . $id . ']', null);
            $this->error(lang('ds_common_del_succ'));
        }
        else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * 批量删除平台客服咨询
     */
    public function del_consult_batch()
    {
        $ids = $_POST['id'];
        if (empty($ids)) {
            $this->error(lang('ds_common_del_fail'));
        }
        $result = Model('mallconsult')->delMallConsult(array('mc_id' => array('in', $ids)));
        if ($result) {
            $this->log('删除平台客服咨询' . '[ID:' . implode(',', $ids) . ']', null);
            $this->error(lang('ds_common_del_succ'));
        }
        else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * 咨询类型列表
     */
    public function type_list()
    {
        $model_mct = Model('mallconsulttype');
        if (request()->isPost()) {
            $mctid_array = $_POST['del_id'];
            if (!is_array($mctid_array)) {
                $this->error(lang('param_error'));
            }
            foreach ($mctid_array as $val) {
                if (!is_numeric($val)) {
                    $this->error(lang('param_error'));
                }
            }

            $result = $model_mct->delMallConsultType(array('mct_id' => array('in', $mctid_array)));

            if ($result) {
                $this->log('删除平台客服咨询类型 ID:' . implode(',', $mctid_array), 1);
                $this->error(lang('ds_common_del_succ'));
            }
            else {
                $this->log('删除平台客服咨询类型 ID:' . implode(',', $mctid_array), 0);
                $this->error(lang('ds_common_del_fail'));
            }
        }
        $type_list = $model_mct->getMallConsultTypeList(array(), 'mct_id,mct_name,mct_sort');
        $this->assign('type_list', $type_list);
        $this->setAdminCurItem('type_list');
        return $this->fetch();
    }

    /**
     * 新增咨询类型
     */
    public function type_add()
    {
        if (request()->isPost()) {
            // 验证
            $obj_validate = new Validate();
            $data=[
                'mct_name'=>$_POST["mct_name"],
                'mct_sort'=>$_POST["mct_sort"]
            ];
            $rule=[
                ['mct_name','require','请填写咨询类型名称'],
                ['mct_sort','require|number','请正确填写咨询类型排序']
            ];

            $error = $obj_validate->check($data,$rule);
            if (!$error) {
                $this->error(lang('ds_common_op_fail') . $obj_validate->getError());
            }
            $insert = array();
            $insert['mct_name'] = trim($_POST['mct_name']);
            $insert['mct_introduce'] = $_POST['mct_introduce'];
            $insert['mct_sort'] = intval($_POST['mct_sort']);
            $result = Model('mallconsulttype')->addMallConsultType($insert);
            if ($result) {
                $this->log('新增咨询类型', 1);
                $this->success(lang('ds_common_save_succ'),'mallconsult/type_list');
            }
            else {
                $this->log('新增咨询类型', 0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
        $this->setAdminCurItem('type_add');
        return $this->fetch();
    }

    /**
     * 编辑咨询类型
     */
    public function type_edit()
    {
        $model_mct = Model('mallconsulttype');
        if (request()->isPost()) {
            // 验证
            $obj_validate = new Validate();
            $data=[
                'mct_name'=>$_POST["mct_name"],
                'mct_sort'=>$_POST["mct_sort"]
            ];
            $rule=[
                ['mct_name','require','请填写咨询类型名称'],
                ['mct_sort','require|number','请正确填写咨询类型排序']
            ];

            $error = $obj_validate->check($data,$rule);
            if (!$error) {
                $this->error(lang('ds_common_op_fail') . $obj_validate->getError());
            }
            $where = array();
            $where['mct_id'] = intval($_POST['mct_id']);
            $update = array();
            $update['mct_name'] = trim($_POST['mct_name']);
            $update['mct_introduce'] = $_POST['mct_introduce'];
            $update['mct_sort'] = intval($_POST['mct_sort']);
            $result = $model_mct->editMallConsultType($where, $update);
            if ($result) {
                $this->log('编辑平台客服咨询类型 ID:' . $where['mct_id'], 1);
                $this->success(lang('ds_common_op_succ'), ('mallconsult/type_list'));
            }
            else {
                $this->log('编辑平台客服咨询类型 ID:' . $where['mct_id'], 0);
                $this->error(lang('ds_common_op_fail'));
            }
        }

        $mct_id = intval(input('param.mct_id'));
        if ($mct_id <= 0) {
            $this->error(lang('param_error'));
        }
        $mct_info = $model_mct->getMallConsultTypeInfo(array('mct_id' => $mct_id));
        $this->assign('mct_info', $mct_info);
        $this->setAdminCurItem('type_edit');
        return $this->fetch();
    }

    /**
     * 删除咨询类型
     */
    public function type_del()
    {
        $mct_id = intval(input('param.mct_id'));
        if ($mct_id <= 0) {
            $this->error(lang('param_error'));
        }
        $result = Model('mallconsulttype')->delMallConsultType(array('mct_id' => $mct_id));
        if ($result) {
            $this->log('删除平台客服咨询类型 ID:' . $mct_id, 1);
            $this->error(lang('ds_common_del_succ'));
        }
        else {
            $this->log('删除平台客服咨询类型 ID:' . $mct_id, 0);
            $this->error(lang('ds_common_del_fail'));
        }
    }

    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '平台客服咨询列表', 'url' => url('mallconsult/index')
            ), array(
                'name' => 'type_list', 'text' => '平台咨询类型', 'url' => url('mallconsult/type_list')
            ), array(
                'name' => 'type_add', 'text' => '新增类型', 'url' => url('mallconsult/type_add')
            ),
        );
        if(request()->action() == 'type_edit')
        $menu_array[] = array(
            'name' => 'type_edit', 'text' => '编辑类型', 'url' =>''
        );
        return $menu_array;
    }
}