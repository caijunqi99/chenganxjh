<?php

namespace app\admin\controller;


use think\Lang;
use think\Validate;

class Promotionbundling extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/zh-cn/promotionbundling.lang.php');
    }


    /**
     * 套餐管理
     */
    public function bundling_quota()
    {
        //自动开启优惠套装
        if (intval(input('param.promotion_allow')) === 1) {
            $model_setting = Model('config');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateConfig($update_array);
        }

        $model_bundling = Model('pbundling');

        // 查询添加
        $where = array();
        if (input('param.store_name') != '') {
            $where['store_name'] = array('like', '%' . trim(input('param.store_name')) . '%');
        }
        if (is_numeric(input('param.state'))) {
            $where['bl_state'] = intval(input('param.state'));
        }

        $bundling_quota_list = $model_bundling->getBundlingQuotaList($where);
        $page = $model_bundling->page_info->render();
        $this->assign('show_page', $page);

        // 状态数组
        $state_array = array(0 => lang('bundling_state_0'), 1 => lang('bundling_state_1'));
        $this->assign('state_array', $state_array);
        $this->setAdminCurItem('bundling_quota');
        $this->assign('list', $bundling_quota_list);
        return $this->fetch();
    }


    /**
     * 活动管理
     */
    public function index()
    {
        $model_bundling = Model('pbundling');

        // 查询添加
        $where = '';
        if (input('param.bundling_name') != '') {
            $where['bl_name'] = array('like', '%' . trim(input('param.bundling_name')) . '%');
        }
        if (input('param.store_name') != '') {
            $where['store_name'] = array('like', '%' . trim(input('param.store_name')) . '%');
        }
        if (is_numeric(input('param.state'))) {
            $where['bl_state'] = input('param.state');
        }
        $bundling_list = $model_bundling->getBundlingList($where);
        $bundling_list = array_under_reset($bundling_list, 'bl_id');
        $this->assign('show_page', $model_bundling->page_info->render());
        if (!empty($bundling_list)) {
            $blid_array = array_keys($bundling_list);
            $bgoods_array = $model_bundling->getBundlingGoodsList(array(
                                                                      'bl_id' => array('in', $blid_array)
                                                                  ), 'bl_id,goods_id,count(*) as count', 'bl_appoint desc', 'bl_id');
            $bgoods_array = array_under_reset($bgoods_array, 'bl_id');
            foreach ($bundling_list as $key => $val) {
                $bundling_list[$key]['goods_id'] = isset($bgoods_array[$val['bl_id']]['goods_id'])?$bgoods_array[$val['bl_id']]['goods_id']:'';
                $bundling_list[$key]['count'] = isset($bgoods_array[$val['bl_id']]['count'])?$bgoods_array[$val['bl_id']]['count']:'';
            }
        }
        $this->assign('list', $bundling_list);

        // 状态数组
        $state_array = array(0 => lang('bundling_state_0'), 1 => lang('bundling_state_1'));
        $this->assign('state_array', $state_array);


        // 输出自营店铺IDS
        // $this->assign('flippedOwnShopIds', array_flip(model('store')->getOwnShopIds()));
        $this->assign('flippedOwnShopIds', '');
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 设置
     */
    public function bundling_setting()
    {
        // 实例化模型
        $model_setting = Model('config');

        if (request()->isPost()) {
            // 验证
            $obj_validate = new Validate();
            $data = [
                'promotion_bundling_price' => $_POST["promotion_bundling_price"],
                'promotion_bundling_sum' => $_POST["promotion_bundling_sum"],
                'promotion_bundling_goods_sum' => $_POST["promotion_bundling_goods_sum"]
            ];
            $rule = [
                ['promotion_bundling_price', 'require|number', lang('bundling_price_error')],
                ['promotion_bundling_sum', 'require|number', lang('bundling_sum_error')],
                ['promotion_bundling_goods_sum', 'require|number', lang('bundling_goods_sum_error')]
            ];
            $error = $obj_validate->check($data, $rule);
            if (!$error) {
                $this->error($obj_validate->getError());
            }

            $data['promotion_bundling_price'] = intval($_POST['promotion_bundling_price']);
            $data['promotion_bundling_sum'] = intval($_POST['promotion_bundling_sum']);
            $data['promotion_bundling_goods_sum'] = intval($_POST['promotion_bundling_goods_sum']);

            $return = $model_setting->updateConfig($data);
            if ($return) {
                $this->log(lang('ds_set') . lang('ds_promotion_bundling'));
                $this->success(lang('ds_common_op_succ'));
            }
            else {
                $this->error(lang('ds_common_op_fail'));
            }
        }
        $this->setAdminCurItem('bundling_setting');
        // 查询setting列表
        $setting = $model_setting->GetListConfig();
        $this->assign('setting', $setting);

        return $this->fetch();
    }

    /**
     * 删除套餐活动
     */
    public function del_bundling()
    {
        $bl_id = intval(input('param.bl_id'));
        if ($bl_id <= 0) {
            $this->error(lang('param_error'));
        }
        $rs = Model('pbundling')->delBundlingForAdmin(array('bl_id' => $bl_id));
        if ($rs) {
            $this->success(lang('ds_common_op_succ'));
        }
        else {
            $this->error(lang('ds_common_op_fail'));
        }
    }

    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('bundling_list'), 'url' => url('promotionbundling/index')
            ), array(
                'name' => 'bundling_quota', 'text' => lang('bundling_quota'),
                'url' => url('promotionbundling/bundling_quota')
            ), array(
                'name' => 'bundling_setting', 'text' => lang('bundling_setting'),
                'url' => url('promotionbundling/bundling_setting')
            ),
        );
        return $menu_array;
    }
}