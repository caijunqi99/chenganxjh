<?php
/**
 * 咨询管理
 * Date: 2017/6/28
 * Time: 12:32
 */

namespace app\home\controller;

use think\Lang;

class Sellerconsult extends BaseSeller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'home/lang/zh-cn/sellerconsult.lang.php');
    }


    /**
     * 商品咨询列表页
     */
    public function index()
    {
        $consult = Model('consult');
        $list_consult = array();
        $where = array();
        if (trim(input('param.type')) == 'to_reply') {
            $where['consult_reply'] = array('eq', '');
        }
        elseif (trim(input('param.type')) == 'replied') {
            $where['consult_reply'] = array('neq', '');
        }
        if (intval(input('param.ctid')) > 0) {
            $where['ct_id'] = intval(input('param.ctid'));
        }
        $where['store_id'] = session('store_id');

        $list_consult = $consult->getConsultList($where, '*', 0);

        $this->assign('list_consult', $list_consult);

        // 咨询类型
        $consult_type = rkcache('consulttype', true);
        $this->assign('consult_type', $consult_type);

        $type = (input('param.type')) ? input('param.type') : 'index';
        /* 设置卖家当前菜单 */
        $this->setSellerCurMenu('seller_consult');
        /* 设置卖家当前栏目 */
        $this->setSellerCurItem($type);
        return $this->fetch($this->template_dir . 'consult_list');
    }

    /**
     * 商品咨询删除处理
     */
    public function drop_consult()
    {
        $ids = trim(input('param.id'));
        if ($ids < 0) {
            showDialog(lang('para_error'), '', 'error');
        }
        $consult = Model('consult');
        $id_array = explode(',', $ids);
        $where = array();
        $where['store_id'] = session('store_id');
        $where['consult_id'] = array('in', $id_array);
        $state = $consult->delConsult($where);
        if ($state) {
            showDialog(lang('store_consult_drop_success'), 'reload', 'succ');
        }
        else {
            showDialog(lang('store_consult_drop_fail'));
        }
    }

    /**
     * 回复商品咨询表单页
     */
    public function reply_consult()
    {
        $consult = Model('consult');
        $list_consult = array();
        $search_array = array();
        $search_array['consult_id'] = intval(input('param.id'));
        $search_array['store_id'] = session('store_id');
        $consult_info = $consult->getConsultInfo($search_array);
        $this->assign('consult', $consult_info);
        return $this->fetch($this->template_dir . 'consult_reply');
    }

    /**
     * 商品咨询回复内容的保存处理
     */
    public function reply_save()
    {
        $consult_id = intval(input('consult_id'));
        if ($consult_id <= 0) {
            showDialog(lang('wrong_argument'));
        }
        $consult = Model('consult');
        $update = array();
        $update['consult_reply'] = $_POST['content'];
        $condtion = array();
        $condtion['store_id'] = session('store_id');
        $condtion['consult_id'] = $consult_id;
        $state = $consult->editConsult($condtion, $update);
        if ($state) {
            $consult_info = $consult->getConsultInfo(array('consult_id' => $consult_id));
            // 发送用户消息
            $param = array();
            $param['code'] = 'consult_goods_reply';
            $param['member_id'] = $consult_info['member_id'];
            $param['param'] = array(
                'goods_name' => $consult_info['goods_name'], 'consult_url' => url('memberconsult/my_consult')
            );
            \mall\queue\QueueClient::push('sendMemberMsg', $param);

            showDialog(lang('ds_common_op_succ'), 'reload', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        }
        else {
            showDialog(lang('ds_common_op_fail'));
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param array $array 附加菜单
     * @return
     */
    protected function getSellerItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '全部咨询', 'url' => url('Sellerconsult/index')
            ), array(
                'name' => 'to_reply', 'text' => '未回复咨询', 'url' => url('Sellerconsult/index', 'type=to_reply')
            ), array(
                'name' => 'replied', 'text' => '已回复咨询', 'url' => url('Sellerconsult/index', 'type=replied')
            )
        );
        return $menu_array;
    }
}