<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 15:37
 */

namespace app\admin\controller;


use think\Lang;

class Promotionmansong extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/zh-cn/promotionmansong.lang.php');
    }


    /**
     * 活动列表
     **/
    public function index()
    {
        //自动开启满就送
        if (intval(input('param.promotion_allow')) === 1) {
            $model_setting = Model('config');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateConfigg($update_array);
        }
        $model_mansong = Model('pmansong');

        $param = array();
        if (!empty(input('param.mansong_name'))) {
            $param['mansong_name'] = array('like', '%' . input('param.mansong_name') . '%');
        }
        if (!empty(input('param.store_name'))) {
            $param['store_name'] = array('like', '%' . input('param.store_name') . '%');
        }
        if (!empty(input('param.state'))) {
            $param['state'] = input('param.state');
        }
        $mansong_list = $model_mansong->getMansongList($param, 10);
        $this->assign('list', $mansong_list);
        $this->assign('show_page', $model_mansong->page_info->render());
        $this->assign('mansong_state_array', $model_mansong->getMansongStateArray());


        $this->setAdminCurItem('index');
        // 输出自营店铺IDS
        $this->assign('flippedOwnShopIds', array_flip(model('store')->getOwnShopIds()));
        return $this->fetch();
    }

    /**
     * 活动详细信息
     * temp
     **/
    public function mansong_detail()
    {
        $mansong_id = intval(input('param.mansong_id'));

        $model_mansong = Model('pmansong');
        $model_mansong_rule = Model('pmansongrule');

        $mansong_info = $model_mansong->getMansongInfoByID($mansong_id);
        if (empty($mansong_info)) {
            $this->error(lang('param_error'));
        }
        $this->assign('mansong_info', $mansong_info);

        $param = array();
        $param['mansong_id'] = $mansong_id;
        $rule_list = $model_mansong_rule->getMansongRuleListByID($mansong_id);
        $this->assign('list', $rule_list);
        $this->setAdminCurItem('mansong_detail');

        return $this->fetch();
    }

    /**
     * 满即送活动取消
     **/
    public function mansong_cancel()
    {
        $mansong_id = intval($_POST['mansong_id']);
        $model_mansong = Model('pmansong');
        $result = $model_mansong->cancelMansong(array('mansong_id' => $mansong_id));
        if ($result) {
            $this->log('取消满即送活动，活动编号' . $mansong_id);
            $this->success(lang('ds_common_op_succ'));
        }
        else {
            $this->error(lang('ds_common_op_fail'));
        }
    }

    /**
     * 满即送活动删除
     **/
    public function mansong_del()
    {
        $mansong_id = intval($_POST['mansong_id']);
        $model_mansong = Model('pmansong');
        $result = $model_mansong->delMansong(array('mansong_id' => $mansong_id));
        if ($result) {
            $this->log('删除满即送活动，活动编号' . $mansong_id);
            $this->success(lang('ds_common_op_succ'));
        }
        else {
            $this->error(lang('ds_common_op_fail'));
        }
    }


    /**
     * 套餐管理
     **/
    public function mansong_quota()
    {
        $model_mansong_quota = Model('pmansongquota');

        $param = array();
        if (!empty(input('param.store_name'))) {
            $param['store_name'] = array('like', '%' . input('param.store_name') . '%');
        }
        $list = $model_mansong_quota->getMansongQuotaList($param, 10, 'quota_id desc');
        $this->assign('list', $list);
        $this->assign('show_page', $model_mansong_quota->page_info->render());
        $this->setAdminCurItem('mansong_quota');

        return $this->fetch();

    }

    /**
     * 设置
     **/
    public function mansong_setting()
    {

        $model_setting = Model('config');
        $setting = $model_setting->GetListConfig();
        $this->assign('setting', $setting);

        $this->setAdminCurItem('mansong_setting');
        return $this->fetch();
    }

    public function mansong_setting_save()
    {

        $promotion_mansong_price = intval($_POST['promotion_mansong_price']);
        if ($promotion_mansong_price === 0) {
            $promotion_mansong_price = 20;
        }

        $model_setting = Model('config');
        $update_array = array();
        $update_array['promotion_mansong_price'] = $promotion_mansong_price;

        $result = $model_setting->updateConfig($update_array);
        if ($result === true) {
            $this->log(lang('ds_config').lang('ds_promotion_mansong,mansong_price'));
            $this->success(lang('setting_save_success'));
        }
        else {
            $this->error(lang('setting_save_fail'));
        }
    }

    /**
     * 页面内导航菜单
     *
     * @param string $menu_key 当前导航的menu_key
     * @param array $array 附加菜单
     * @return
     */
    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('mansong_list'), 'url' => url('promotionmansong/index')
            ), array(
                'name' => 'mansong_quota', 'text' => lang('mansong_quota'),
                'url' => url('promotionmansong/mansong_quota')
            ), array(
                'name' => 'mansong_setting', 'text' => lang('mansong_setting'),
                'url' => url('promotionmansong/mansong_setting')
            ),
        );
        if (request()->action() == 'mansong_detail') {
            $menu_array[] = array(
                'name' => 'mansong_detail', 'text' => lang('mansong_detail'),
                'url' => url('promotion_mansong/mansong_detail')
            );
        }
        return $menu_array;
    }
}