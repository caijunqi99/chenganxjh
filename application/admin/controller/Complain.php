<?php

namespace app\admin\controller;


use think\Lang;
use think\Validate;

class Complain extends AdminControl
{
    //定义投诉状态常量
    const STATE_NEW = 10;
    const STATE_APPEAL = 20;
    const STATE_TALK = 30;
    const STATE_HANDLE = 40;
    const STATE_FINISH = 99;
    const STATE_ACTIVE = 2;
    const STATE_UNACTIVE = 1;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/zh-cn/complain.lang.php');
    }


    /*
     * 未处理的投诉列表
     */
    public function complain_new_list()
    {
        $this->get_complain_list(self::STATE_NEW, 'complain_new_list');
        return $this->fetch('complain_list');
    }

    /*
     * 待申诉的投诉列表
     */
    public function complain_appeal_list()
    {
        $this->get_complain_list(self::STATE_APPEAL, 'complain_appeal_list');
        return $this->fetch('complain_list');
    }

    /*
     * 对话的投诉列表
     */
    public function complain_talk_list()
    {
        $this->get_complain_list(self::STATE_TALK, 'complain_talk_list');
        return $this->fetch('complain_list');
    }

    /*
     * 待仲裁的投诉列表
     */
    public function complain_handle_list()
    {
        $this->get_complain_list(self::STATE_HANDLE, 'complain_handle_list');
        return $this->fetch('complain_list');
    }

    /*
     * 已关闭的投诉列表
     */
    public function complain_finish_list()
    {
        $this->get_complain_list(self::STATE_FINISH, 'complain_finish_list');
        return $this->fetch('complain_list');
    }

    /*
     * 获取投诉列表
     */
    private function get_complain_list($complain_state, $op)
    {
        $model_complain = Model('complain');
        //搜索条件
        $condition = array();
        $condition['complain_accuser'] = trim(input('param.input_complain_accuser'));
        $condition['complain_accused'] = trim(input('param.input_complain_accused'));
        $condition['complain_subject_content'] = trim(input('param.input_complain_subject_content'));
        $condition['complain_datetime_start'] = strtotime(input('param.input_complain_datetime_start'));
        $condition['complain_datetime_end'] = strtotime(input('param.input_complain_datetime_end'));
        if ($op === 'complain_finish_list') {
            $condition['order'] = 'complain_id desc';
        } else {
            $condition['order'] = 'complain_id asc';
        }
        $condition['complain_state'] = $complain_state;
        $complain_list = $model_complain->getComplain($condition, 10);
        $this->setAdminCurItem($op);
        $this->assign('op', $op);
        $this->assign('list', $complain_list);
        $this->assign('show_page', $model_complain->page_info->render());
    }

    /*
     * 进行中的投诉
     */
    public function complain_progress()
    {
        $complain_id = intval(input('param.complain_id'));
        //获取投诉详细信息
        $complain_info = $this->get_complain_info($complain_id);
        //获取订单详细信息
        $order_info = $this->get_order_info($complain_info['order_id']);
        //获取投诉的商品列表
        $complain_goods_list = $this->get_complain_goods_list($complain_info['order_goods_id']);
        $this->assign('complain_goods_list', $complain_goods_list);
        if (intval($complain_info['complain_subject_id']) == 1) {//退款信息
            $model_refund = Model('refundreturn');
            $model_refund->getRefundStateArray();//向模板页面输出退款退货状态
            $list = $model_refund->getComplainRefundList($order_info, $complain_info['order_goods_id']);
            $this->assign('refund_list', $list['refund']);//已退或处理中商品
            $this->assign('refund_goods', $list['goods']);//可退商品
        }
        $this->setAdminCurItem('complain_progress');
        $this->assign('order_info', $order_info);
        $this->assign('complain_info', $complain_info);
        return $this->fetch('complain_info');
    }

    /*
     * 审核提交的投诉
     */
    public function complain_verify()
    {
        $complain_id = intval($_POST['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        if (intval($complain_info['complain_state']) === self::STATE_NEW) {
            $model_complain = Model('complain');
            $update_array = array();
            $update_array['complain_state'] = self::STATE_APPEAL;
            $update_array['complain_handle_datetime'] = time();
            $update_array['complain_handle_member_id'] = $this->get_admin_id();
            $update_array['complain_active'] = self::STATE_ACTIVE;
            $where_array = array();
            $where_array['complain_id'] = $complain_id;
            if ($model_complain->updateComplain($update_array, $where_array)) {
                $this->log(lang('complain_verify_success') . '[' . $complain_id . ']', 1);

                // 发送商家消息
                $param = array();
                $param['code'] = 'complain';
                $param['store_id'] = $complain_info['accused_id'];
                $param['param'] = array(
                    'complain_id' => $complain_id
                );
                \mall\queue\QueueClient::push('sendStoreMsg', $param);

                $this->success(lang('complain_verify_success'), url('complain/complain_new_list'));
            } else {
                $this->error(lang('complain_verify_fail'), url('complain/complain_new_list'));
            }
        } else {
            $this->error(lang('param_error'), '');
        }
    }

    /*
     * 关闭投诉
     */
    public function complain_close()
    {
        //获取输入的数据
        $complain_id = intval($_POST['complain_id']);
        $final_handle_message = trim($_POST['final_handle_message']);
        $date=[
            'final_handle_message'=>$final_handle_message
        ];
        //验证输入的数据
        $rule=[
            ['final_handle_message','require|max:255|min:1',lang('final_handle_message_error')]
        ];
        $obj_validate = new Validate();
        $error = $obj_validate->check($date,$rule);
        if (!$error) {
            $this->error($obj_validate->getError());
        }
        $complain_info = $this->get_complain_info($complain_id);
        $current_state = intval($complain_info['complain_state']);
        if ($current_state !== self::STATE_FINISH) {
            $model_complain = Model('complain');
            $update_array = array();
            $update_array['complain_state'] = self::STATE_FINISH;
            $update_array['final_handle_message'] = $final_handle_message;
            $update_array['final_handle_datetime'] = time();
            $update_array['final_handle_member_id'] = $this->get_admin_id();
            $where_array = array();
            $where_array['complain_id'] = $complain_id;
            if ($model_complain->updateComplain($update_array, $where_array)) {
                if (intval($complain_info['complain_subject_id']) == 1) {//退款信息
                    $order = $this->get_order_info($complain_info['order_id']);
                    $model_refund = Model('refundreturn');
                    $list = $model_refund->getComplainRefundList($order, $complain_info['order_goods_id']);
                    $refund_goods = $list['goods'];//可退商品
                    if (!empty($refund_goods) && is_array($refund_goods)) {
                        $checked_goods = $_POST['checked_goods'];
                        foreach ($refund_goods as $key => $value) {
                            $goods_id = $value['rec_id'];//订单商品表编号
                            if (!empty($checked_goods) && array_key_exists($goods_id, $checked_goods)) {//验证提交的商品属于订单
                                $refund_array = array();
                                $refund_array['refund_type'] = '1';//类型:1为退款,2为退货
                                $refund_array['seller_state'] = '2';//卖家处理状态:1为待审核,2为同意,3为不同意
                                $refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成
                                $refund_array['order_lock'] = '1';//锁定类型:1为不用锁定,2为需要锁定
                                $refund_array['refund_amount'] = dsPriceFormat($value['goods_refund']);
                                $refund_array['reason_id'] = '0';
                                $refund_array['reason_info'] = '投诉成功';
                                $refund_array['buyer_message'] = '投诉成功,待管理员确认退款';
                                $refund_array['seller_message'] = '投诉成功,待管理员确认退款';
                                $refund_array['add_time'] = time();
                                $refund_array['seller_time'] = time();
                                $model_refund->addRefundReturn($refund_array, $order, $value);
                            }
                        }
                    }
                }
                $this->log(lang('complain_close_success') . '[' . $complain_id . ']', 1);
                $this->success(lang('complain_close_success'), $this->get_complain_state_link($current_state));
            } else {
                $this->error(lang('complain_close_fail'), $this->get_complain_state_link($current_state));
            }
        } else {
            $this->error(lang('param_error'), '');
        }
    }

    /*
     * 投诉主题列表
     */
    public function complain_subject_list()
    {
        /*
         * 获得举报主题列表
         */
        $model_complain_subject = Model('complainsubject');
        //搜索条件
        $condition = array();
        $condition['order'] = 'complain_subject_id asc';
        $condition['complain_subject_state'] = 1;
        $complain_subject_list = $model_complain_subject->getComplainSubject($condition, 10);
        $this->setAdminCurItem('complain_subject_list');
        $this->assign('list', $complain_subject_list);
        $this->assign('show_page', $model_complain_subject->page_info->render());
        return $this->fetch('complain_subject_list');
    }

    /*
     * 添加投诉主题页面
     */
    public function complain_subject_add()
    {
        $this->setAdminCurItem('complain_subject_add');
        return $this->fetch();
    }

    /*
     * 保存添加的投诉主题
     */
    public function complain_subject_save()
    {
        //获取提交的内容
        $input['complain_subject_content'] = trim($_POST['complain_subject_content']);
        $input['complain_subject_desc'] = trim($_POST['complain_subject_desc']);
        //验证提交的内容
        $data = [
            'complain_subject_content' => $input['complain_subject_content'],
            'complain_subject_desc' => $input['complain_subject_desc'],
        ];
        $rule = [
            [
                'complain_subject_content',
                'require|max:50|min:1',
                lang('complain_subject_content_error')
            ],
            [
                'complain_subject_desc',
                'require|max:50|min:1',
                lang('complain_subject_desc_error')
            ]
        ];
        $obj_validate = new Validate();
        $error = $obj_validate->check($data, $rule);
        if (!$error) {
            $this->error($obj_validate->getError());
        } else {
            //验证成功保存
            $input['complain_subject_state'] = 1;
            $model_complain_subject = Model('complainsubject');
            $model_complain_subject->saveComplainSubject($input);
            $this->log(lang('complain_subject_add_success') . '[' . $_POST['complain_subject_content'] . ']', 1);
            $this->success(lang('complain_subject_add_success'), 'complain/complain_subject_list');
        }
    }

    /*
     * 删除投诉主题,伪删除只是修改标记
     */
    public function complain_subject_drop()
    {
        $complain_subject_id = trim($_POST['complain_subject_id']);
        if (empty($complain_subject_id)) {
            $this->error(lang('param_error'));
        }
        $model_complain_subject = Model('complainsubject');
        $update_array = array();
        $update_array['complain_subject_state'] = 2;
        $where_array = array();
        $where_array['in_complain_subject_id'] = "'" . implode("','", explode(',', $complain_subject_id)) . "'";
        if ($model_complain_subject->updateComplainSubject($update_array, $where_array)) {
            $this->log(lang('complain_subject_delete_success') . '[ID:' . $_POST['complain_subject_id'] . ']', 1);
            $this->success(lang('complain_subject_delete_success'), 'complain/complain_subject_list');
        } else {
            $this->error(lang('complain_subject_delete_fail'), 'complain/complain_subject_list');
        }
    }

    /*
     * 根据投诉id获取投诉对话列表
     */
    public function get_complain_talk()
    {
        $complain_id = intval(input('param.complain_id'));
        $complain_info = $this->get_complain_info($complain_id);
        $complain_talk_list = $this->get_talk_list($complain_id);
        $talk_list = array();
        $i = 0;
        foreach ($complain_talk_list as $talk) {
            $talk_list[$i]['css'] = $talk['talk_member_type'];
            $talk_list[$i]['talk'] = date("Y-m-d", $talk['talk_datetime']);
            switch ($talk['talk_member_type']) {
                case 'accuser':
                    $talk_list[$i]['talk'] .= lang('complain_accuser');
                    break;
                case 'accused':
                    $talk_list[$i]['talk'] .= lang('complain_accused');
                    break;
                case 'admin':
                    $talk_list[$i]['talk'] .= lang('complain_admin');
                    break;
                default:
                    $talk_list[$i]['talk'] .= lang('complain_unknow');
            }
            if (intval($talk['talk_state']) === 2) {
                $talk['talk_content'] = lang('talk_forbit_message');
                $forbit_link = '';
            } else {
                $forbit_link = "&nbsp;&nbsp;<a href='#' onclick=forbit_talk(" . $talk['talk_id'] . ")>" . lang('complain_text_forbit') . "</a>";
            }
            $talk_list[$i]['talk'] .= '(' . $talk['talk_member_name'] . ')' . lang('complain_text_say') . ':' . $talk['talk_content'] . $forbit_link;
            $i++;
        }

        echo json_encode($talk_list);
    }

    /*
     * 发布投诉对话
     */
    public function publish_complain_talk()
    {
        $complain_id = intval(input('param.complain_id'));
        $complain_talk = trim(input('param.complain_talk'));
        $talk_len = strlen($complain_talk);
        if ($talk_len > 0 && $talk_len < 255) {
            $complain_info = $this->get_complain_info($complain_id);
            $model_complain_talk = Model('complaintalk');
            $param = array();
            $param['complain_id'] = $complain_id;
            $param['talk_member_id'] = $this->get_admin_id();
            $param['talk_member_name'] = $this->get_admin_name();
            $param['talk_member_type'] = 'admin';

            $param['talk_content'] = $complain_talk;
            $param['talk_state'] = 1;
            $param['talk_admin'] = 0;
            $param['talk_datetime'] = time();
            if ($model_complain_talk->saveComplainTalk($param)) {
                echo json_encode('success');
            } else {
                echo json_encode('error2');
            }
        } else {
            echo json_encode('error1');
        }
    }

    /*
     * 屏蔽对话
     */
    public function forbit_talk()
    {
        $talk_id = intval($_POST['talk_id']);
        if (!empty($talk_id) && is_integer($talk_id)) {
            $model_complain_talk = Model('complaintalk');
            $update_array = array();
            $update_array['talk_state'] = 2;
            $update_array['talk_admin'] = $this->get_admin_id();
            $where_array = array();
            $where_array['talk_id'] = $talk_id;
            if ($model_complain_talk->updateComplainTalk($update_array, $where_array)) {
                echo json_encode('success');
            } else {
                echo json_encode('error2');
            }
        } else {
            echo json_encode('error1');
        }
    }

    /**
     * 投诉设置
     **/
    public function complain_setting()
    {
        //读取设置内容 $list_setting
        $model_setting = Model('config');
        $list_setting = $model_setting->getListconfig();
        $this->assign('list_setting', $list_setting);
        $this->setAdminCurItem('complain_setting');
        return $this->fetch('complain_setting');
    }

    /**
     * 投诉设置保存
     **/
    public function complain_setting_save()
    {
        $model_setting = Model('config');
        $complain_time_limit = intval($_POST['complain_time_limit']);
        if (empty($complain_time_limit)) {
            //如果输入不合法默认30天
            $complain_time_limit = 2592000;
        } else {
            $complain_time_limit = $complain_time_limit * 86400;
        }
        $update_array['complain_time_limit'] = $complain_time_limit;
        if ($model_setting->updateconfig($update_array)) {
            $this->log(lang('complain_setting_save_success'), 1);
            $this->success(lang('complain_setting_save_success'));
        } else {
            $this->error(lang('complain_setting_save_fail'));
        }
    }

    /*
     * 获取订单信息
     */
    private function get_order_info($order_id)
    {
        $model_order = Model('order');
        $order_info = $model_order->getOrderInfo(array('order_id' => $order_id), array('order_goods'));
        if (empty($order_info)) {
            $this->error(lang('param_error'));
        }
        $order_info['order_state_text'] = orderState($order_info);
        return $order_info;
    }

    /*
     * 获取投诉信息
     */
    private function get_complain_info($complain_id)
    {
        $model_complain = Model('complain');
        $complain_info = $model_complain->getoneComplain($complain_id);
        if (empty($complain_info)) {
            $this->error(lang('param_error'));
        }
        $complain_info['complain_state_text'] = $this->get_complain_state_text($complain_info['complain_state']);
        return $complain_info;
    }

    /*
     * 获取投诉商品列表
     */
    private function get_complain_goods_list($order_goods_id)
    {
        $model_order = Model('order');
        $param = array();
        $param['rec_id'] = $order_goods_id;
        $complain_goods_list = $model_order->getOrderGoodsList($param);
        return $complain_goods_list;
    }

    /*
     * 获取对话列表
     */
    private function get_talk_list($complain_id)
    {
        $model_complain_talk = Model('complaintalk');
        $param = array();
        $param['complain_id'] = $complain_id;
        $talk_list = $model_complain_talk->getComplainTalk($param);
        return $talk_list;
    }

    /*
     * 获得投诉状态文本
     */
    private function get_complain_state_text($complain_state)
    {
        switch (intval($complain_state)) {
            case self::STATE_NEW:
                return lang('complain_state_new');
                break;
            case self::STATE_APPEAL:
                return lang('complain_state_appeal');
                break;
            case self::STATE_TALK:
                return lang('complain_state_talk');
                break;
            case self::STATE_HANDLE:
                return lang('complain_state_handle');
                break;
            case self::STATE_FINISH:
                return lang('complain_state_finish');
                break;
            default:
                $this->error(lang('param_error'), '');
        }
    }

    /*
     * 获得投诉状态文本
     */
    private function get_complain_state_link($complain_state)
    {
        switch (intval($complain_state)) {
            case self::STATE_NEW:
                return 'complain/complain_new_list';
                break;
            case self::STATE_APPEAL:
                return 'complain/complain_appeal_list';
                break;
            case self::STATE_TALK:
                return 'complain/complain_talk_list';
                break;
            case self::STATE_HANDLE:
                return 'complain/complain_handle_list';
                break;
            case self::STATE_FINISH:
                return 'complain/complain_finish_list';
                break;
            default:
                $this->error(lang('param_error'));
        }
    }

    /*
     * 获得管理员id
     */
    private function get_admin_id()
    {
        $admin_info = $this->getAdminInfo();
        return $admin_info['admin_id'];
    }

    /*
     * 获得管理员name
     */
    private function get_admin_name()
    {
        $admin_info = $this->getAdminInfo();
        return $admin_info['admin_name'];
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param array $array 附加菜单
     * @return
     */
    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'complain_new_list',
                'text' => lang('complain_new_list'),
                'url' => url('complain/complain_new_list')
            ),
            array(
                'name' => 'complain_appeal_list',
                'text' => lang('complain_appeal_list'),
                'url' => url('complain/complain_appeal_list')
            ),
            array(
                'name' => 'complain_talk_list',
                'text' => lang('complain_talk_list'),
                'url' => url('complain/complain_talk_list')
            ),
            array(
                'name' => 'complain_handle_list',
                'text' => lang('complain_handle_list'),
                'url' => url('complain/complain_handle_list')
            ),
            array(
                'name' => 'complain_finish_list',
                'text' => lang('complain_finish_list'),
                'url' => url('complain/complain_finish_list')
            ),
            array(
                'name' => 'complain_subject_list',
                'text' => lang('complain_subject_list'),
                'url' => url('complain/complain_subject_list')
            ),
            array(
                'name' => 'complain_subject_add',
                'text' => lang('complain_subject_add'),
                'url' => url('complain/complain_subject_add')
            ),
            array(
                'name' => 'complain_setting',
                'text' => lang('complain_setting'),
                'url' => url('complain/complain_setting')
            )
        );

        if (request()->action() == 'complain_progress') {
            $menu_array[] = array(
                'name' => 'complain_progress',
                'text' => lang('complain_progress'),
                'url' => '###'
            );
        }
        return $menu_array;
    }
}
