<?php

namespace app\office\controller;


use think\Validate;

class Promotionbooth extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    public function index()
    {

        //自动开启优惠套装
        if (intval(input('param.promotion_allow')) === 1) {
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
        /**
         * 处理商品分类
         */
        $gcid = intval(input('param.choose_gcid'));
        $choose_gcid = $gcid > 0 ? $gcid : 0;
        $gccache_arr = Model('goodsclass')->getGoodsclassCache($choose_gcid, 3);
        $this->assign('gc_json', json_encode($gccache_arr['showclass']));
        $this->assign('gc_choose_json', json_encode($gccache_arr['choose_gcid']));

        $model_booth = Model('pbooth');
        $where = array();
        if (intval(input('param.choose_gcid')) > 0) {
            $where['gc_id'] = intval(input('param.choose_gcid'));
        }
        $goods_list = $model_booth->getBoothGoodsList($where, 'goods_id', 10);
        if (!empty($goods_list)) {
            $goodsid_array = array();
            foreach ($goods_list as $val) {
                $goodsid_array[] = $val['goods_id'];
            }
            $goods_list = Model('goods')->getGoodsList(array('goods_id' => array('in', $goodsid_array)));
        }
        $this->assign('gc_list', Model('goodsclass')->getGoodsClassForCacheModel());
        $this->assign('goods_list', $goods_list);
        $this->assign('page', $model_booth->page_info->render());

        $this->setAdminCurItem('index');
        // 输出自营店铺IDS
        $this->assign('flippedOwnShopIds', array_flip(model('store')->getOwnShopIds()));
        return $this->fetch();
    }

    /**
     * 套餐列表
     */
    public function booth_quota()
    {
        $model_booth = Model('pbooth');
        $where = array();
        if (input('param.store_name') != '') {
            $where['store_name'] = array('like', '%' . trim(input('param.store_name')) . '%');
        }
        $booth_list = $model_booth->getBoothQuotaList($where, '*', 10);

        // 状态数组
        $state_array = array(0 => lang('close'), 1 => lang('open'));
        $this->assign('state_array', $state_array);

        $this->setAdminCurItem('booth_quota');
        $this->assign('booth_list', $booth_list);
        $this->assign('show_page', $model_booth->page_info->render());
        return $this->fetch();
    }

    /**
     * 删除推荐商品
     */
    public function del_goodsOp()
    {
        $where = array();
        // 验证id是否正确
        if (is_array($_POST['goods_id'])) {
            foreach ($_POST['goods_id'] as $val) {
                if (!is_numeric($val)) {
                    showDialog(lang('ds_common_del_fail'));
                }
            }
            $where['goods_id'] = array('in', $_POST['goods_id']);
        }
        elseif (intval(input('param.goods_id')) >= 0) {
            $where['goods_id'] = intval(input('param.goods_id'));
        }
        else {
            showDialog(lang('ds_common_del_fail'));
        }

        $rs = Model('pbooth')->delBoothGoods($where);
        if ($rs) {
            showDialog(lang('ds_common_del_succ'), 'reload', 'succ');
        }
        else {
            showDialog(lang('ds_common_del_fail'));
        }
    }

    /**
     * 设置
     */
    public function booth_setting()
    {
        // 实例化模型
        $model_setting = Model('config');

        if (request()->isPost()) {
            // 验证
            $obj_validate = new Validate();
            $data = [
                'promotion_booth_price' => $_POST["promotion_booth_price"],
                'promotion_booth_goods_sum' => $_POST["promotion_booth_goods_sum"],
            ];
            $rule = [
                ['promotion_booth_price', 'require|number', '请填写展位价格'],
                ['promotion_booth_goods_sum', 'require|number', '不能为空，且不小于1的整数']
            ];
            $error = $obj_validate->check($data, $rule);
            if (!$error) {
                $this->error($obj_validate->getError());
            }

            $data['promotion_booth_price'] = intval($_POST['promotion_booth_price']);
            $data['promotion_booth_goods_sum'] = intval($_POST['promotion_booth_goods_sum']);

            $return = $model_setting->updateConfig($data);
            if ($return) {
                $this->log(lang('ds_set') . ' 推荐展位');
                $this->error(lang('ds_common_op_succ'));
            }
            else {
                $this->error(lang('ds_common_op_fail'));
            }
        }

        // 查询setting列表
        $setting = $model_setting->GetListConfig();
        $this->assign('setting', $setting);
        $this->setAdminCurItem('booth_setting');
        return $this->fetch();
    }

    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '商品列表', 'url' => url('promotionbooth/index')
            ), array(
                'name' => 'booth_quota', 'text' => '套餐列表', 'url' => url('promotionbooth/booth_quota')
            ), array(
                'name' => 'booth_setting', 'text' => '设置', 'url' => url('promotionbooth/booth_setting')
            ),
        );
        return $menu_array;
    }
}