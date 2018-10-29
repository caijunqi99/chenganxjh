<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Membermessage extends MobileMember
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 统计未读消息
     * @return [type] [description]
     */
    public function showReceivedNewNum()
    {
        $message = array(
            // 新接收到普通的消息
            'newcommon' =>$this->receivedCommonNewNum(),
            //新接收到系统的消息
            'newsystem' =>$this->receivedSystemNewNum(),
            // 新接收到卖家的消息
            'newpersonal' =>$this->receivedPersonalNewNum(),
            //会员是否允许发送站内信
            'isallowsend' =>$this->allowSendMessage($this->member_info['member_id'])
        );
        output_data($message);
    }


    /**
     * 系统站内信列表
     *
     * @param
     * @return
     */
    public function systemmsg()
    {
        $model_message = Model('message');
        $condition = array(
            'from_member_id' => '0', 
            'message_type' => '1', 
            'to_member_id' => $this->member_info['member_id'], 
            'no_del_member_id' => $this->member_info['member_id']
        );
        $message_array = $model_message->listMessage($condition, 100);
        halt($message_array);
        if (!empty($message_array) && is_array($message_array)) {
            foreach ($message_array as $k => $v) {
                $v['message_open'] = '0';
                if (!empty($v['read_member_id'])) {
                    $tmp_readid_arr = explode(',', $v['read_member_id']);
                    if (in_array($this->member_info['member_id'], $tmp_readid_arr)) {
                        $v['message_open'] = '1';
                    }
                }
                $message_array[$k]['message_time'] = date('Y-m-d',$v['message_time']);
                $message_array[$k]['message_update_time'] = date('Y-m-d',$v['message_update_time']);
                $v['from_member_name'] = '系统消息';
                $message_array[$k] = $v;
            }
        }
        output_data($message_array);
    }

    /**
     * 系统站内信查看操作
     *
     * @param
     * @return
     */
    public function showmsgbatch()
    {
        $model_message = Model('message');
        $message_id = intval(input('param.message_id'));
        $drop_type = trim(input('param.type'));
       
        if (!in_array($drop_type, array('msg_system', 'msg_seller')) || $message_id <= 0) {
            output_error('参数错误!');
        }
        //查询站内信
        $param = array();
        $param['message_id'] = $message_id;
        $param['to_member_id'] = $this->member_info['member_id'];
        $param['no_del_member_id'] = $this->member_info['member_id'];
        $message_info = $model_message->getRowMessage($param);
        if (empty($message_info)) {
            output_error('没有符合条件的记录!');
        }
        if ($drop_type == 'msg_system') {
            $message_info['from_member_name'] = '系统消息';
        }
        if ($drop_type == 'msg_seller') {
            //查询店铺信息
            $model_store = Model('store');
            $store_info = $model_store->getStoreInfo(array('member_id' => "{$message_info['from_member_id']}"));
            $message_info['from_member_name'] = $store_info['store_name'];
            $message_info['store_id'] = $store_info['store_id'];
        }
        $message_list[0] = $message_info;
        $this->assign('message_list', $message_list);//站内信列表
        //更新为已读信息
        $tmp_readid_str = '';
        if (!empty($message_info['read_member_id'])) {
            $tmp_readid_arr = explode(',', $message_info['read_member_id']);
            if (!in_array($this->member_info['member_id'], $tmp_readid_arr)) {
                $tmp_readid_arr[] = $this->member_info['member_id'];
            }
            foreach ($tmp_readid_arr as $readid_k => $readid_v) {
                if ($readid_v == '') {
                    unset($tmp_readid_arr[$readid_k]);
                }
            }
            $tmp_readid_arr = array_unique($tmp_readid_arr);//去除相同
            sort($tmp_readid_arr);//排序
            $tmp_readid_str = "," . implode(',', $tmp_readid_arr) . ",";
        }
        else {
            $tmp_readid_str = ",".$this->member_info['member_id'].",";
        }
        $model_message->updateCommonMessage(array('read_member_id' => $tmp_readid_str), array('message_id' => "{$message_id}"));
        //更新未读站内信数量cookie值
        $cookie_name = 'msgnewnum' . $this->member_info['member_id'];
        $countnum = $model_message->countNewMessage($this->member_info['member_id']);
        output_data(array(
                //剩余未查看消息数量
                'newsystem'=>$countnum,
                'drop_type' =>$drop_type,
            )
        );
    }

    /**
     * 删除批量站内信
     * dropbatchmsg/drop_type/msg_system/message_id/2.html');
     * @return [type] [description]
     */
    public function dropbatchmsg()
    {
        $message_id = trim(input('param.message_id'));
        $drop_type = trim(input('param.drop_type'));
        if (!in_array($drop_type, array('msg_system', 'msg_seller')) || empty($message_id)) {
            output_error('参数错误!');
        }
        $messageid_arr = explode(',', $message_id);
        $messageid_str = '';
        if (!empty($messageid_arr)) {
            $messageid_str = "'" . implode("','", $messageid_arr) . "'";
        }
        $model_message = Model('message');
        $param = array('message_id_in' => $messageid_str);
        if ($drop_type == 'msg_system') {
            $param['message_type'] = '1';
            $param['from_member_id'] = '0';
        }
        if ($drop_type == 'msg_seller') {
            $param['message_type'] = '2';
        }
        $drop_state = $model_message->dropBatchMessage($param, $this->member_info['member_id']);
        if ($drop_state) {
            output_data(array('drop_state'=>true));
        }else{
            output_error('删除失败');

        }
    }

    /**
     * 收到(普通)站内信列表
     *
     * @param
     * @return
     */
    public function message()
    {
        $model_message = Model('message');
       
        $message_array = $model_message->listMessage(array('message_type' => '2', 'to_member_id_common' => $this->member_info['member_id'], 'no_message_state' => '2'), 10);
        $this->assign('show_page', $model_message->page_info->render());
        $this->assign('message_array', $message_array);

        // 新消息数量
        $this->showReceivedNewNum();

        $this->assign('drop_type', 'msg_list');
        $this->setMemberCurItem('message');
        $this->setMemberCurMenu('member_message');

       return $this->fetch($this->template_dir.'message');
    }

    /**
     * 收到(私信)站内信列表
     *
     * @param
     * @return
     */
    public function personalmsg()
    {
        $model_message = Model('message');
        $message_array = $model_message->listMessage(array('message_type' => '0', 'to_member_id_common' => $this->member_info['member_id'], 'no_message_state' => '2'), 10);
        $this->assign('show_page',$model_message->page_info->render());
        $this->assign('message_array', $message_array);

        // 新消息数量
        $this->showReceivedNewNum();

        $this->assign('drop_type', 'msg_list');
        $this->setMemberCurItem('close');
        $this->setMemberCurMenu('member_message');
       return $this->fetch($this->template_dir.'message');
    }

    /**
     * 查询会员是否允许发送站内信
     *
     * @return bool
     */
    private function allowSendMessage($member_id)
    {
        $member_info = Model('member')->getMemberInfoByID($member_id, 'is_allowtalk');
        if ($member_info['is_allowtalk'] == '1') {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 私人站内信列表
     *
     * @param
     * @return
     */
    public function privatemsg()
    {
        $model_message = Model('message');
        $message_array = $model_message->listMessage(array('message_type_in' => '0,2', 'from_member_id' => $this->member_info['member_id'], 'no_message_state' => '1'), 10);
        $this->assign('show_page',$model_message->page_info->render());
        $this->assign('message_array', $message_array);

        // 新消息数量
        $this->showReceivedNewNum();

        $this->assign('drop_type', 'msg_private');
        $this->setMemberCurItem('private');
        $this->setMemberCurMenu('member_message');
       return $this->fetch($this->template_dir.'sendlist');
    }



    /**
     * 发送站内信页面
     *
     * @param
     * @return
     */
    public function sendmsg()
    {
        $referer_url = getReferer();
        //查询会员是否允许发送站内信
        $isallowsend = $this->allowSendMessage($this->member_info['member_id']);
        if (!$isallowsend) {
            $this->error(lang('home_message_noallowsend'));
        }
        $model_member = Model('member');
        $member_name_string = '';
        $member_id = intval(input('param.member_id'));
        if ($member_id > 0) {
            //连接发放站内信页面
            $member_info = $model_member->getMemberInfoByID($member_id);
            if (empty($member_info)) {
                $this->error(lang('wrong_argument'));
            }
            $member_name_string = $member_info['member_name'];
            $this->assign('member_name', $member_name_string);
        }
        //批量给好友发放站内信页面
        $friend_model = Model('snsfriend');
        $friend_list = $friend_model->listFriend(array('friend_frommid' => $this->member_info['member_id']));
        $this->assign('friend_list', $friend_list);

        // 新消息数量
        $this->showReceivedNewNum();
        $this->setMemberCurItem('sendmsg');
        $this->setMemberCurMenu('member_message');
       return $this->fetch($this->template_dir.'send');
    }

    /**
     * 站内信保存操作
     *
     * @param
     * @return
     */
    public function savemsg()
    {
        //查询会员是否允许发送站内信
        $isallowsend = $this->allowSendMessage($this->member_info['member_id']);
        if (!$isallowsend) {
            showDialog(lang('home_message_noallowsend'));
        }
            $obj_validate = new Validate();
        $data=[
            'to_member_name'=>$_POST["to_member_name"],
            'msg_content'=>$_POST["msg_content"]
        ];
        $rule=[
            ['to_member_name','require',lang('home_message_receiver_null')],
            ['msg_content','require',lang('home_message_content_null')],
        ];
            $error = $obj_validate->check($data,$rule);
            if (!$error) {
                showDialog($obj_validate->getError());
            }
            $msg_content = trim($_POST['msg_content']);
            $membername_arr = explode(',', $_POST['to_member_name']);

            if (in_array(session('member_name'), $membername_arr)) {
                unset($membername_arr[array_search(session('member_name'), $membername_arr)]);
            }
            //查询有效会员
            $member_model = Model('member');
            $member_list = $member_model->getMemberList(array('member_name' => array('in', $membername_arr)));

            if (!empty($member_list)) {
                $model_message = Model('message');
                foreach ($member_list as $k => $v) {
                    $insert_arr = array();
                    $insert_arr['from_member_id'] = $this->member_info['member_id'];
                    $insert_arr['from_member_name'] = session('member_name');
                    $insert_arr['member_id'] = $v['member_id'];
                    $insert_arr['to_member_name'] = $v['member_name'];
                    $insert_arr['msg_content'] = $msg_content;
                    $insert_arr['message_type'] = intval($_POST['msg_type']);
                    $return = $model_message->saveMessage($insert_arr);
                }
            }
            else {
                showDialog(lang('home_message_receiver_error'));
            }
            if (input('param.type') == 'sns_board') {
                $insert_arr['msg_id'] = $return;

                $insert_arr['msg_content'] = $insert_arr['msg_content'];
                $data = json_encode($insert_arr);
                $js = "leaveMsgSuccess(" . $data . ")";
                showDialog(lang('home_message_send_success'), '', 'succ', $js);
            }
            else {
                showDialog(lang('home_message_send_success'), url('membermessage/privatemsg'), 'succ');
            }
    }

    /**
     * 普通站内信查看操作
     *
     * @param
     * @return
     */
    public function showmsgcommon()
    {
        $model_message = Model('message');
        $message_id = intval(input('param.message_id'));
        $drop_type = trim(input('param.type'));
        if (!in_array($drop_type, array('msg_list')) || $message_id <= 0) {
            $this->error(lang('wrong_argument'));
        }
        //查询站内信
        $param = array();
        $param['message_id'] = "$message_id";
        $param['to_member_id_common'] = "{$this->member_info['member_id']}";
        $param['no_message_state'] = "2";
        $message_info = $model_message->getRowMessage($param);
        if (empty($message_info)) {
            $this->error(lang('home_message_no_record'));
        }
        unset($param);
        if ($message_info['message_parent_id'] > 0) {
            //查询该站内信的父站内信
            $parent_array = $model_message->getRowMessage(array('message_id' => "{$message_info['message_parent_id']}", 'message_type' => '0', 'no_message_state' => '2'));
            //查询该站内信的回复站内信
            $reply_array = $model_message->listMessage(array('message_parent_id' => "{$message_info['message_parent_id']}", 'message_type' => '0', 'no_message_state' => '2'));
        }
        else {//此信息为父站内信
            $parent_array = $message_info;
            //查询回复站内信
            $reply_array = $model_message->listMessage(array('message_parent_id' => "$message_id", 'message_type' => '0', 'no_message_state' => '2'));
        }
        //处理获取站内信数组
        $message_list = array();
        if (!empty($reply_array)) {
            foreach ($reply_array as $k => $v) {
                $message_list[$v['message_id']] = $v;
            }
        }
        if (!empty($parent_array)) {
            $message_list[$parent_array['message_id']] = $parent_array;
        }
        unset($parent_array);
        unset($reply_array);
        //更新已读状态
        $messageid_arr = array_keys($message_list);
        if (!empty($messageid_arr)) {
            $messageid_str = "'" . implode("','", $messageid_arr) . "'";
            $model_message->updateCommonMessage(array('message_open' => '1'), array('message_id_in' => "$messageid_str"));
        }
        //更新未读站内信数量cookie值
        $cookie_name = 'msgnewnum' . $this->member_info['member_id'];
        $countnum = $model_message->countNewMessage($this->member_info['member_id']);
        Cookie($cookie_name, $countnum, 2 * 3600);//保存2小时
        $this->assign('message_num', $countnum);
        $this->assign('message_id', $message_id);//点击的该条站内信编号
        $this->assign('message_list', $message_list);//站内信列表

        // 新消息数量
        $this->showReceivedNewNum();
        $this->setMemberCurMenu('member_message');
        $this->setMemberCurItem('showmsg');
       return $this->fetch($this->template_dir.'member_message.view');
    }



    /**
     * 短消息回复保存
     *
     * @param
     * @return
     */
    public function savereply()
    {
        //查询会员是否允许发送站内信
        $isallowsend = $this->allowSendMessage($this->member_info['member_id']);
        if (!$isallowsend) {
            if (input('param.inajax') == 1) {
                showDialog(lang('home_message_noallowsend'));
            }
            else {
                $this->error(lang('home_message_noallowsend'), 'membermessage/message');
            }
        }
        if ($_POST['form_submit'] == 'ok') {
            $message_id = intval($_POST["message_id"]);
            if ($message_id <= 0) {
                $this->error(lang('wrong_argument'), url('membermessage/message'));
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["msg_content"], "require" => "true",
                    "message" => lang('home_message_reply_content_null')
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                if (input('param.inajax') == 1) {
                    showDialog(lang('error'));
                }
                else {
                    $this->error(lang('error') . $error);
                }
            }
            $model_message = Model('message');
            //查询站内信
            $param = array();
            $param['message_id'] = "$message_id";
            $param['no_message_state'] = "2";//未删除
            $message_info = $model_message->getRowMessage($param);
            if (empty($message_info)) {
                if (input('param.inajax') == 1) {
                    showDialog(lang('home_message_no_record'));
                }
                else {
                    $this->error(lang('home_message_no_record') . $error);
                }
            }
            //不能回复自己的站内信
            if ($message_info['from_member_id'] == $this->member_info['member_id']) {
                $this->error(lang('home_message_no_record'));
            }
            $insert_arr = array();
            if ($message_info['message_parent_id'] > 0) {
                $insert_arr['message_parent_id'] = $message_info['message_parent_id'];
            }
            else {
                $insert_arr['message_parent_id'] = $message_info['message_id'];
            }
            $insert_arr['from_member_id'] = $this->member_info['member_id'];
            $insert_arr['from_member_name'] = session('member_name');
            $insert_arr['member_id'] = $message_info['from_member_id'];
            $insert_arr['to_member_name'] = $message_info['from_member_name'];
            $insert_arr['msg_content'] = $_POST['msg_content'];
            $insert_state = $model_message->saveMessage($insert_arr);
            if ($insert_state) {
                //更新父类站内信更新时间
                $update_arr = array();
                $update_arr['message_update_time'] = time();
                $update_arr['message_open'] = 1;
                $model_message->updateCommonMessage($update_arr, array('message_id' => "{$insert_arr['message_parent_id']}"));
            }
            if (input('param.inajax') == 1) {
                $insert_arr['msg_id'] = $insert_state;

                $insert_arr['msg_content'] = $insert_arr['msg_content'];
                $data = json_encode($insert_arr);
                $js = "replyMsgSuccess(" . $data . ")";
                showDialog(lang('home_message_send_success'), '', 'succ', $js);
            }
            else {
                $this->error(lang('home_message_send_success'), 'membermessage/privatemsg');
            }
        }
        else {
            if (input('param.inajax') == 1) {
                showDialog(lang('home_message_reply_command_wrong'));
            }
            else {
                $this->error(lang('home_message_reply_command_wrong'));
            }
        }
    }

    /**
     * 删除普通信
     */
    public function dropcommonmsg()
    {
        $message_id = trim(input('param.message_id'));
        $drop_type = trim(input('param.drop_type'));
        if (!in_array($drop_type, array('msg_private', 'msg_list', 'sns_msg')) || empty($message_id)) {
            $this->error(lang('wrong_argument'));
        }
        $messageid_arr = explode(',', $message_id);
        $messageid_str = '';
        if (!empty($messageid_arr)) {
            $messageid_str = "'" . implode("','", $messageid_arr) . "'";
        }
        $model_message = Model('message');
        $param = array('message_id_in' => $messageid_str);
        if ($drop_type == 'msg_private') {
            $param['from_member_id'] = $this->member_info['member_id'];
        }
        elseif ($drop_type == 'msg_list') {
            $param['to_member_id_common'] = $this->member_info['member_id'];
        }
        elseif ($drop_type == 'sns_msg') {
            $param['from_to_member_id'] = $this->member_info['member_id'];
        }
        $drop_state = $model_message->dropCommonMessage($param, $drop_type);
        if ($drop_state) {
            //更新未读站内信数量cookie值
            $cookie_name = 'msgnewnum' . $this->member_info['member_id'];
            $countnum = $model_message->countNewMessage($this->member_info['member_id']);
            cookie($cookie_name, $countnum, 2 * 3600);//保存2小时
            showDialog(lang('home_message_delete_success'), 'reload', 'succ');
        }
        else {
            showDialog(lang('home_message_delete_fail'), '', 'error');
        }
    }



    /**
     * 消息接收设置
     *
     * 注意：由于用户消息模板不是循环输出，所以每增加一种消息模板，
     *     都需要在模板（member_message.setting）中需要手工添加该消息模板的选项卡，
     *     在control部分也要添加相关的验证，否则默认开启无法关闭。
     */
    public function setting()
    {
        //error_reporting(LIBXML_ERR_FATAL);
        $model_membermsgsetting = Model('membermsgsetting');
            $insert = array(
                // 付款成功提醒
                array(
                    'mmt_code' => 'order_payment_success', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['order_payment_success'])?intval($_POST['order_payment_success']):0
                ), // 商品出库提醒
                array(
                    'mmt_code' => 'order_deliver_success', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['order_deliver_success'])?intval($_POST['order_deliver_success']):0
                ), // 余额变动提醒
                array(
                    'mmt_code' => 'predeposit_change', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['predeposit_change'])?intval($_POST['predeposit_change']):0
                ), // 充值卡余额变动提醒
                array(
                    'mmt_code' => 'recharge_card_balance_change', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['recharge_card_balance_change'])?intval($_POST['recharge_card_balance_change']):0
                ), // 代金券使用提醒
                array(
                    'mmt_code' => 'voucher_use', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['voucher_use'])?intval($_POST['voucher_use']):0
                ), // 退款退货提醒
                array(
                    'mmt_code' => 'refund_return_notice', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['refund_return_notice'])?intval($_POST['refund_return_notice']):0
                ), // 到货通知提醒
                array(
                    'mmt_code' => 'arrival_notice', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['arrival_notice'])?intval($_POST['arrival_notice']):0
                ), // 商品咨询回复提醒
                array(
                    'mmt_code' => 'consult_goods_reply', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['consult_goods_reply'])?intval($_POST['consult_goods_reply']):0
                ), // 平台客服回复提醒
                array(
                    'mmt_code' => 'consult_mall_reply', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['consult_mall_reply'])?intval($_POST['consult_mall_reply']):0
                ), // 代金券即将到期
                array(
                    'mmt_code' => 'voucher_will_expire', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['voucher_will_expire'])?intval($_POST['voucher_will_expire']):0
                ), // 兑换码即将到期提醒
                array(
                    'mmt_code' => 'vr_code_will_expire', 'member_id' => $this->member_info['member_id'],
                    'is_receive' => isset($_POST['vr_code_will_expire'])?intval($_POST['vr_code_will_expire']):0
                ),
            );
        if (request()->isPost()) {
            db('membermsgsetting')->where(array( 'member_id' => $this->member_info['member_id']))->delete();
            $result = $model_membermsgsetting->addMemberMsgSettingAll($insert);
            if ($result) {
                showDialog(lang('ds_common_save_succ'), '', 'succ');
            }
            else {
                showDialog(lang('ds_common_save_fail'));
            }
        }
        // 新消息数量
        $this->showReceivedNewNum();

        $setting_list = $model_membermsgsetting->getMemberMsgSettingList(array('member_id' => $this->member_info['member_id']));
        if(empty($setting_list)){
            $setting_list=$insert;
        }
        $setting_array = array();
        if (!empty($setting_list)) {
            foreach ($setting_list as $val) {
                $setting_array[$val['mmt_code']] = intval($val['is_receive']);
            }
        }
        $this->assign('setting_array', $setting_array);

        $this->setMemberCurItem('setting');
        $this->setMemberCurMenu('member_message');
       return $this->fetch($this->template_dir.'setting');
    }



    /**
     * 统计收到站内信未读条数
     *
     * @return int
     */
    private function receivedCommonNewNum()
    {
        $model_message = Model('message');
        $countnum = $model_message->countMessage(array('message_type' => '2', 'to_member_id_common' => $this->member_info['member_id'], 'no_message_state' => '2', 'message_open_common' => '0'));
        return $countnum;
    }

    /**
     * 统计系统站内信未读条数
     *
     * @return int
     */
    private function receivedSystemNewNum()
    {
        $message_model = Model('message');
        $condition_arr = array();
        $condition_arr['message_type'] = '1';//系统消息
        $condition_arr['to_member_id'] = $this->member_info['member_id'];
        $condition_arr['no_del_member_id'] = $this->member_info['member_id'];
        $condition_arr['no_read_member_id'] = $this->member_info['member_id'];
        $countnum = $message_model->countMessage($condition_arr);
        return $countnum;
    }

    /**
     * 统计私信未读条数
     *
     * @return int
     */
    private function receivedPersonalNewNum()
    {
        $model_message = Model('message');
        $countnum = $model_message->countMessage(array('message_type' => '0', 'to_member_id_common' => $this->member_info['member_id'], 'no_message_state' => '2', 'message_open_common' => '0'));
        return $countnum;
    }


}