<?php

namespace app\admin\controller;

use think\Controller;

class AdminControl extends Controller {

    /**
     * 管理员资料 name id group
     */
    protected $admin_info;

    protected $permission;
    public function _initialize() {
        $this->admin_info = $this->systemLogin();
        $config_list = rkcache('config', true);
        config($config_list);
        if ($this->admin_info['admin_id'] != 1) {
            // 验证权限
            $this->checkPermission();
            // dump($this->permission);
        }
        $this->setMenuList();
    }

    /**
     * 取得当前管理员信息
     *
     * @param
     * @return 数组类型的返回结果
     */
    protected final function getAdminInfo() {
        return $this->admin_info;
    }

    /**
     * 系统后台登录验证
     *
     * @param
     * @return array 数组类型的返回结果
     */
    protected final function systemLogin() {
        $admin_info = array(
            'admin_id' => session('admin_id'),
            'admin_name' => session('admin_name'),
            'admin_gid' => session('admin_gid'),
            'admin_is_super' => session('admin_is_super'),
        );
        if (empty($admin_info['admin_id']) || empty($admin_info['admin_name']) || !isset($admin_info['admin_gid']) || !isset($admin_info['admin_is_super'])) {
            session(null);
            $this->redirect('Admin/Login/index');
        }

        return $admin_info;
    }

    public function setMenuList() {
        $menu_list = $this->menuList();

        $menu_list=$this->parseMenu($menu_list);
        $this->assign('menu_list', $menu_list);
    }

    /**
     * 验证当前管理员权限是否可以进行操作
     *
     * @param string $link_nav
     * @return
     */
    protected final function checkPermission($link_nav = null){
        if ($this->admin_info['admin_is_super'] == 1) return true;

        $act = request()->controller();
        //halt($act);
        $op = request()->action();
        if (empty($this->permission)){
            $gadmin = db('gadmin')->where(array('gid'=>$this->admin_info['admin_gid']))->find();
            $permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
            $this->permission = $permission = explode('|',$permission);
        }else{
            $permission = $this->permission;
        }
        //显示隐藏小导航，成功与否都直接返回
        if (is_array($link_nav)){
            if (!in_array("{$link_nav['act']}.{$link_nav['op']}",$permission) && !in_array($link_nav['act'],$permission)){
                return false;
            }else{
                return true;
            }
        }

        //以下几项不需要验证
        $tmp = array('Index','Dashboard','Login');
        if (in_array($act,$tmp)) return true;
        if (in_array($act,$permission) || in_array("$act.$op",$permission)){
            return true;
        }else{
            $extlimit = array('ajax','export_step1');
            if (in_array($op,$extlimit) && (in_array($act,$permission) || strpos(serialize($permission),'"'.$act.'.'))){
                return true;
            }
            //带前缀的都通过
            foreach ($permission as $v) {
                if (!empty($v) && strpos("$act.$op",$v.'_') !== false) {
                    return true;break;
                }
            }
        }
        $this->error(lang('ds_assign_right'));

    }

    /**
     * 过滤掉无权查看的菜单
     *
     * @param array $menu
     * @return array
     */
    private final function parseMenu($menu = array()) {
        if ($this->admin_info['admin_is_super'] == 1) {
            return $menu;
        }
        foreach ($menu as $k => $v) {
            foreach ($v['children'] as $ck => $cv) {
                $tmp = explode(',', $cv['args']);
                //以下几项不需要验证
                $except = array('Index', 'Dashboard', 'Login');
                if (in_array($tmp[1], $except))
                    continue;
                if (!in_array($tmp[1], array_values($this->permission))) {
                    unset($menu[$k]['children'][$ck]);
                }
            }
            if (empty($menu[$k]['children'])) {
                unset($menu[$k]);
                unset($menu[$k]['children']);
            }
        }
        return $menu;
    }

    /**
     * 记录系统日志
     *
     * @param $lang 日志语言包
     * @param $state 1成功0失败null不出现成功失败提示
     * @param $admin_name
     * @param $admin_id
     */
    protected final function log($lang = '', $state = 1, $admin_name = '', $admin_id = 0) {
        if ($admin_name == '') {
            $admin_name = session('admin_name');
            $admin_id = session('admin_id');
        }
        $data = array();
        if (is_null($state)) {
            $state = null;
        } else {
            $state = $state ? '' : lang('ds_fail');
        }
        $data['content'] = $lang . $state;
        $data['admin_name'] = $admin_name;
        $data['createtime'] = TIMESTAMP;
        $data['admin_id'] = $admin_id;
        $data['ip'] = request()->ip();
        $data['url'] = request()->controller() . '&' . request()->action();
        return db('adminlog')->insertGetId($data);
    }

    /**
     * 添加到任务队列
     *
     * @param array $goods_array
     * @param boolean $ifdel 是否删除以原记录
     */
    protected function addcron($data = array(), $ifdel = false) {
        $model_cron = Model('cron');
        if (isset($data[0])) { // 批量插入
            $where = array();
            foreach ($data as $k => $v) {
                if (isset($v['content'])) {
                    $data[$k]['content'] = serialize($v['content']);
                }
                // 删除原纪录条件
                if ($ifdel) {
                    $where[] = '(type = ' . $data['type'] . ' and exeid = ' . $data['exeid'] . ')';
                }
            }
            // 删除原纪录
            if ($ifdel) {
                $model_cron->delCron(implode(',', $where));
            }
            $model_cron->addCronAll($data);
        } else { // 单条插入
            if (isset($data['content'])) {
                $data['content'] = serialize($data['content']);
            }
            // 删除原纪录
            if ($ifdel) {
                $model_cron->delCron(array('type' => $data['type'], 'exeid' => $data['exeid']));
            }
            $model_cron->addCron($data);
        }
    }

    /**
     * 当前选中的栏目
     */
    protected function setAdminCurItem($curitem = '') {
        $this->assign('admin_item', $this->getAdminItemList());
        $this->assign('curitem', $curitem);
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        return array();
    }

    /*
     * 侧边栏列表
     */

    function menuList() {
        return array(
            'dashboard' => array(
                'name' => 'dashboard',
                'text' => lang('ds_dashboard'),
                'children' => array(
                    'welcome' => array(
                        'text' => lang('ds_welcome'),
                        'args' => 'welcome,Dashboard,dashboard',
                    ),
                    /*
                    'aboutus' => array(
                        'text' => lang('ds_aboutus'),
                        'args' => 'aboutus,dashboard,dashboard',
                    ),
                     */
                    'config' => array(
                        'text' => lang('ds_base'),
                        'args' => 'base,Config,dashboard',
                    ),
                    'member' => array(
                        'text' => lang('ds_member_manage'),
                        'args' => 'member,Member,dashboard',
                    ),
                    'packages' => array(
                        'text' => lang('packages_manage'),
                        'args' => 'ap_manage,Pkgs,dashboard',
                    ),
                ),
            ),
            'setting' => array(
                'name' => 'setting',
                'text' => lang('ds_setting'),
                'children' => array(
                    'config' => array(
                        'text' => lang('ds_base'),
                        'args' => 'base,Config,setting',
                    ),
                    'account' => array(
                        'text' => lang('ds_account'),
                        'args' => 'qq,Account,setting',
                    ),
                    'upload_set' => array(
                        'text' => lang('ds_upload_set'),
                        'args' => 'default_thumb,Upload,setting',
                    ),
                    'seo' => array(
                        'text' => lang('ds_seo_set'),
                        'args' => 'index,Seo,setting',
                    ),
                    'message' => array(
                        'text' => lang('ds_message'),
                        'args' => 'email,Message,setting',
                    ),
                    'payment' => array(
                        'text' => lang('ds_payment'),
                        'args' => 'index,Payment,setting',
                    ),
                    'admin' => array(
                        'text' => lang('ds_admin'),
                        'args' => 'admin,Admin,setting',
                    ),
                    'express' => array(
                        'text' => lang('ds_express'),
                        'args' => 'index,Express,setting',
                    ),
                    'waybill' => array(
                        'text' => lang('ds_waybill'),
                        'args' => 'index,Waybill,setting',
                    ),
                    'Region' => array(
                        'text' => lang('ds_region'),
                        'args' => 'index,Region,setting',
                    ),
                    'offpayarea' => array(
                        'text' => lang('ds_offpayarea'),
                        'args' => 'index,Offpayarea,setting',
                    ),
                    'cache' => array(
                        'text' => lang('ds_cache'),
                        'args' => 'clear,Cache,setting',
                    ),
                    'db' => array(
                        'text' => lang('ds_db'),
                        'args' => 'db,Db,setting',
                    ),
                    'admin_log' => array(
                        'text' => lang('ds_adminlog'),
                        'args' => 'loglist,Adminlog,setting',
                    ),
                ),
            ),
            'member' => array(
                'name' => 'member',
                'text' => lang('ds_member'),
                'children' => array(
                    'member' => array(
                        'text' => lang('ds_member_manage'),
                        'args' => 'member,Member,member',
                    ),
                    'membergrade' => array(
                        'text' => lang('ds_membergrade'),
                        'args' => 'index,Membergrade,member',
                    ),
                    'exppoints' => array(
                        'text' => lang('ds_exppoints'),
                        'args' => 'index,Exppoints,member',
                    ),
                    'notice' => array(
                        'text' => lang('ds_notice'),
                        'args' => 'notice,Notice,member',
                    ),
                    'points' => array(
                        'text' => lang('ds_points'),
                        'args' => 'index,Points,member',
                    ),
                    'predeposit' => array(
                        'text' => lang('ds_predeposit'),
                        'args' => 'pdrecharge_list,Predeposit,member',
                    ),
                    'snsmalbum' => array(
                        'text' => lang('ds_snsmalbum'),
                        'args' => 'index,Snsmalbum,member',
                    ),
                    'snstrace' => array(
                        'text' => lang('ds_snstrace'),
                        'args' => 'index,Snstrace,member',
                    ),
                    'snsmember' => array(
                        'text' => lang('ds_snsmember'),
                        'args' => 'index,Snsmember,member',
                    ),
                    'chatlog' => array(
                        'text' => lang('ds_chatlog'),
                        'args' => 'chatlog,Chatlog,member',
                    ),
                ),
            ),
            'goods' => array(
                'name' => 'goods',
                'text' => lang('ds_goods'),
                'children' => array(
                    'goodsclass' => array(
                        'text' => lang('ds_goodsclass'),
                        'args' => 'goods_class,Goodsclass,goods',
                    ),
                    'Brand' => array(
                        'text' => lang('ds_brand'),
                        'args' => 'index,Brand,goods',
                    ),
                    'Goods' => array(
                        'text' => lang('ds_goods_manage'),
                        'args' => 'index,Goods,goods',
                    ),
                    'Type' => array(
                        'text' => lang('ds_type'),
                        'args' => 'index,Type,goods',
                    ),
                    'Spec' => array(
                        'text' => lang('ds_spec'),
                        'args' => 'index,Spec,goods',
                    ),
                    'album' => array(
                        'text' => lang('ds_album'),
                        'args' => 'index,GoodsAlbum,goods',
                    ),
                ),
            ),
            'store' => array(
                'name' => 'store',
                'text' => lang('ds_store'),
                'children' => array(
                    'Store' => array(
                        'text' => lang('ds_store_manage'),
                        'args' => 'store,Store,store',
                    ),
                    'Storegrade' => array(
                        'text' => lang('ds_storegrade'),
                        'args' => 'index,Storegrade,store',
                    ),
                    'Storeclass' => array(
                        'text' => lang('ds_storeclass'),
                        'args' => 'store_class,Storeclass,store',
                    ),
                    'Storehelp' => array(
                        'text' => lang('ds_Storehelp'),
                        'args' => 'index,Storehelp,store',
                    ),
                    'Storejoin' => array(
                        'text' => lang('ds_storejoin'),
                        'args' => 'index,Storejoin,store',
                    ),
                    'Ownshop' => array(
                        'text' => lang('ds_ownshop'),
                        'args' => 'index,Ownshop,store',
                    ),
                ),
            ),
            'trade' => array(
                'name' => 'trade',
                'text' => lang('ds_trade'),
                'children' => array(
                    'order' => array(
                        'text' => lang('ds_order'),
                        'args' => 'index,Order,trade',
                    ),
                    'vrorder' => array(
                        'text' => lang('ds_vrorder'),
                        'args' => 'index,Vrorder,trade',
                    ),
                    'refund' => array(
                        'text' => lang('ds_refund'),
                        'args' => 'refund_manage,Refund,trade',
                    ),
                    'return' => array(
                        'text' => lang('ds_return'),
                        'args' => 'return_manage,Returnmanage,trade',
                    ),
                    'vrrefund' => array(
                        'text' => lang('ds_vrrefund'),
                        'args' => 'refund_manage,Vrrefund,trade',
                    ),
                    'consulting' => array(
                        'text' => lang('ds_consulting'),
                        'args' => 'Consulting,Consulting,trade',
                    ),
                    'inform' => array(
                        'text' => lang('ds_inform'),
                        'args' => 'inform_list,Inform,trade',
                    ),
                    'evaluate' => array(
                        'text' => lang('ds_evaluate'),
                        'args' => 'evalgoods_list,Evaluate,trade',
                    ),
                    'complain' => array(
                        'text' => lang('ds_complain'),
                        'args' => 'complain_new_list,Complain,trade',
                    ),
                ),
            ),
            'website' => array(
                'name' => 'website',
                'text' => lang('ds_website'),
                'children' => array(
                    'Articleclass' => array(
                        'text' => lang('ds_articleclass'),
                        'args' => 'index,Articleclass,website',
                    ),
                    'Article' => array(
                        'text' => lang('ds_article'),
                        'args' => 'index,Article,website',
                    ),
                    'Document' => array(
                        'text' => lang('ds_document'),
                        'args' => 'index,Document,website',
                    ),
                    'Navigation' => array(
                        'text' => lang('ds_navigation'),
                        'args' => 'index,Navigation,website',
                    ),
                    'Adv' => array(
                        'text' => lang('ds_adv'),
                        'args' => 'ap_manage,Adv,website',
                    ),
                    'Recposition' => array(
                        'text' => lang('ds_recposition'),
                        'args' => 'rec_list,Recposition,website',
                    ),
                    'Link' => array(
                        'text' => lang('ds_link'),
                        'args' => 'index,Link,website',
                    ),
                ),
            ),
            'operation' => array(
                'name' => 'operation',
                'text' => lang('ds_operation'),
                'children' => array(
                    'Operation' => array(
                        'text' => lang('ds_operation_set'),
                        'args' => 'setting,Operation,operation',
                    ),
                    'Inviter' => array(
                        'text' => lang('ds_inviter_set'),
                        'args' => 'setting,Inviter,operation',
                    ),
                    'Groupbuy' => array(
                        'text' => lang('ds_groupbuy'),
                        'args' => 'index,Groupbuy,operation',
                    ),
                    'Vrgroupbuy' => array(
                        'text' => lang('ds_groupbuy_vr'),
                        'args' => 'index,Vrgroupbuy,operation',
                    ),
                    'Xianshi' => array(
                        'text' => lang('ds_promotion_xianshi'),
                        'args' => 'index,Promotionxianshi,operation',
                    ),
                    'Mansong' => array(
                        'text' => lang('ds_promotion_mansong'),
                        'args' => 'index,Promotionmansong,operation',
                    ),
                    'Bundling' => array(
                        'text' => lang('ds_promotion_bundling'),
                        'args' => 'index,Promotionbundling,operation',
                    ),
                    'Booth' => array(
                        'text' => lang('ds_promotion_booth'),
                        'args' => 'index,Promotionbooth,operation',
                    ),
                    'Voucher' => array(
                        'text' => lang('ds_voucher_price_manage'),
                        'args' => 'index,Voucher,operation',
                    ),
                    'Bill' => array(
                        'text' => lang('ds_bill_manage'),
                        'args' => 'index,Bill,operation',
                    ),
                    'Vrbill' => array(
                        'text' => lang('ds_vrbill_manage'),
                        'args' => 'index,Vrbill,operation',
                    ),
                    'Activity' => array(
                        'text' => lang('ds_activity_manage'),
                        'args' => 'index,Activity,operation',
                    ),
                    'Pointprod' => array(
                        'text' => lang('ds_pointprod'),
                        'args' => 'index,Pointprod,operation',
                    ),
                    'Mallconsult' => array(
                        'text' => lang('ds_mall_consult'),
                        'args' => 'index,Mallconsult,operation',
                    ),
                    'rechargecard' => array(
                        'text' => lang('ds_rechargecard'),
                        'args' => 'index,Rechargecard,operation',
                    ),
                    'Delivery' => array(
                        'text' => lang('ds_delivery'),
                        'args' => 'index,Delivery,operation',
                    ),
                ),
            ),
            'stat' => array(
                'name' => 'stat',
                'text' => lang('ds_stat'),
                'children' => array(
                    'stat_general' => array(
                        'text' => lang('ds_statgeneral'),
                        'args' => 'general,Statgeneral,stat',
                    ),
                    'stat_industry' => array(
                        'text' => lang('ds_statindustry'),
                        'args' => 'scale,Statindustry,stat',
                    ),
                    'stat_member' => array(
                        'text' => lang('ds_statmember'),
                        'args' => 'newmember,Statmember,stat',
                    ),
                    'stat_store' => array(
                        'text' => lang('ds_statstore'),
                        'args' => 'newstore,Statstore,stat',
                    ),
                    'stat_trade' => array(
                        'text' => lang('ds_stattrade'),
                        'args' => 'income,Stattrade,stat',
                    ),
                    'stat_goods' => array(
                        'text' => lang('ds_statgoods'),
                        'args' => 'pricerange,Statgoods,stat',
                    ),
                    'stat_marketing' => array(
                        'text' => lang('ds_statmarketing'),
                        'args' => 'promotion,Statmarketing,stat',
                    ),
                    'stat_stataftersale' => array(
                        'text' => lang('ds_stataftersale'),
                        'args' => 'refund,Stataftersale,stat',
                    ),
                ),
            ),
            'mobile' => array(
                'name' => 'mobile',
                'text' => lang('mobile'),
                'children' => array(
                    'mb_category_list' => array(
                        'text' => lang('mb_category_list'),
                        'args' => 'mb_category_list,mbcategorypic,mobile',
                    ),
                    /* 'mb_app'=>array(
                      'text'=>lang('mb_app'),
                      'args' => 'mb_app_list,mbapp,mobile',
                      ), */
                    'mb_feedback' => array(
                        'text' => lang('mb_feedback'),
                        'args' => 'flist,mbfeedback,mobile',
                    ),
                    'mb_payment' => array(
                        'text' => lang('mb_payment'),
                        'args' => 'payment_list,mbpayment,mobile',
                    ),
                ),
            ),
            'wechat' => array(
                'name' => 'wechat',
                'text' => lang('wechat'),
                'children' => array(
                    'wechat_setting' => array(
                        'text' => lang('wechat'),
                        'args' => 'setting,Wechat,wechat',
                    ),
                    'wechat_menu' => array(
                        'text' => lang('wechat_menu'),
                        'args' => 'menu,Wechat,wechat',
                    ),
                    'wechat_keywords' => array(
                        'text' => lang('wechat_keywords'),
                        'args' => 'k_text,Wechat,wechat',
                    ),
                    'wechat_member' => array(
                        'text' => lang('wechat_member'),
                        'args' => 'member,Wechat,wechat',
                    ),
                    'wechat_push' => array(
                        'text' => lang('wechat_push'),
                        'args' => 'SendList,Wechat,wechat',
                    ),
                ),
            ),
        );
    }

    /*
     * 权限选择列表
     */

    function limitList() {
        $_limit = array(
            array('name' => lang('ds_setting'), 'child' => array(
                    array('name' => lang('ds_base'), 'op' => null, 'act' => 'Config'),
                    array('name' => lang('ds_account'), 'op' => null, 'act' => 'Account'),
                    array('name' => lang('ds_upload_set'), 'op' => null, 'act' => 'Upload'),
                    array('name' => lang('ds_seo_set'), 'op' => null, 'act' => 'Seo'),
                    array('name' => lang('ds_payment'), 'op' => null, 'act' => 'Payment'),
                    array('name' => lang('ds_message'), 'op' => null, 'act' => 'Message'),
                    array('name' => lang('ds_express'), 'op' => null, 'act' => 'Express'),
                    array('name' => lang('ds_waybill'), 'op' => null, 'act' => 'Waybill'),
                    array('name' => lang('ds_region'), 'op' => null, 'act' => 'Region'),
                    array('name' => lang('ds_offpayarea'), 'op' => null, 'act' => 'Offpayarea'),
                    array('name' => lang('ds_cache'), 'op' => null, 'act' => 'Cache'),
                    array('name' => lang('ds_adminlog'), 'op' => null, 'act' => 'Adminlog'),
                )),
            array('name' => lang('ds_goods'), 'child' => array(
                    array('name' => lang('ds_goods_manage'), 'op' => null, 'act' => 'Goods'),
                    array('name' => lang('ds_goodsclass'), 'op' => null, 'act' => 'Goodsclass'),
                    array('name' => lang('ds_brand'), 'op' => null, 'act' => 'Brand'),
                    array('name' => lang('ds_type'), 'op' => null, 'act' => 'Type'),
                    array('name' => lang('ds_spec'), 'op' => null, 'act' => 'Spec'),
                    array('name' => lang('ds_album'), 'op' => null, 'act' => 'GoodsAlbum'),
                )),
            array('name' => lang('ds_store'), 'child' => array(
                    array('name' => lang('ds_store_manage'), 'op' => null, 'act' => 'Store'),
                    array('name' => lang('ds_storegrade'), 'op' => null, 'act' => 'Storegrade'),
                    array('name' => lang('ds_storeclass'), 'op' => null, 'act' => 'Storeclass'),
                    array('name' => lang('ds_Storehelp'), 'op' => null, 'act' => 'Storehelp'),
                    array('name' => lang('ds_storejoin'), 'op' => null, 'act' => 'Storejoin'),
                    array('name' => lang('ds_ownshop'), 'op' => null, 'act' => 'Ownshop'),
                )),
            array('name' => lang('ds_member'), 'child' => array(
                    array('name' => lang('ds_member_manage'), 'op' => null, 'act' => 'Member'),
                    array('name' => lang('ds_membergrade'), 'op' => null, 'act' => 'Membergrade'),
                    array('name' => lang('ds_exppoints'), 'op' => null, 'act' => 'Exppoints'),
                    array('name' => lang('ds_points'), 'op' => null, 'act' => 'Points'),
                    array('name' => lang('ds_snsmalbum'), 'op' => null, 'act' => 'Snsmalbum'),
                    array('name' => lang('ds_snstrace'), 'op' => null, 'act' => 'Snstrace'),
                    array('name' => lang('ds_snsmember'), 'op' => null, 'act' => 'Snsmember'),
                    array('name' => lang('ds_predeposit'), 'op' => null, 'act' => 'Predeposit'),
                    array('name' => lang('ds_chatlog'), 'op' => null, 'act' => 'Chatlog'),
                )),
            array('name' => lang('ds_trade'), 'child' => array(
                    array('name' => lang('ds_order'), 'op' => null, 'act' => 'Order'),
                    array('name' => lang('ds_vrorder'), 'op' => null, 'act' => 'Vrorder'),
                    array('name' => lang('ds_refund'), 'op' => null, 'act' => 'Refund'),
                    array('name' => lang('ds_return'), 'op' => null, 'act' => 'Returnmanage'),
                    array('name' => lang('ds_vrrefund'), 'op' => null, 'act' => 'Vrrefund'),
                    array('name' => lang('ds_consulting'), 'op' => null, 'act' => 'Consulting'),
                    array('name' => lang('ds_inform'), 'op' => null, 'act' => 'Inform'),
                    array('name' => lang('ds_evaluate'), 'op' => null, 'act' => 'Evaluate'),
                    array('name' => lang('ds_complain'), 'op' => null, 'act' => 'Complain'),
                )),
            array('name' => lang('ds_website'), 'child' => array(
                    array('name' => lang('ds_articleclass'), 'op' => null, 'act' => 'Articleclass'),
                    array('name' => lang('ds_article'), 'op' => null, 'act' => 'Article'),
                    array('name' => lang('ds_document'), 'op' => null, 'act' => 'Document'),
                    array('name' => lang('ds_navigation'), 'op' => null, 'act' => 'Navigation'),
                    array('name' => lang('ds_adv'), 'op' => null, 'act' => 'Adv'),
                    array('name' => lang('ds_recposition'), 'op' => null, 'act' => 'Recposition'),
                    array('name' => lang('ds_link'), 'op' => null, 'act' => 'Link'),
                )),
            array('name' => lang('ds_operation'), 'child' => array(
                    array('name' => lang('ds_operation_set'), 'op' => null, 'act' => 'Operation'),
                    array('name' => lang('ds_groupbuy'), 'op' => null, 'act' => 'Groupbuy'),
                    array('name' => lang('ds_groupbuy_vr'), 'op' => null, 'act' => 'Vrgroupbuy'),
                    array('name' => lang('ds_activity_manage'), 'op' => null, 'act' => 'Activity'),
                    array('name' => lang('ds_promotion_xianshi'), 'op' => null, 'act' => 'Promotionxianshi'),
                    array('name' => lang('ds_promotion_mansong'), 'op' => null, 'act' => 'Promotionmansong'),
                    array('name' => lang('ds_promotion_bundling'), 'op' => null, 'act' => 'Promotionbundling'),
                    array('name' => lang('ds_promotion_booth'), 'op' => null, 'act' => 'Promotionbooth'),
                    array('name' => lang('ds_pointprod'), 'op' => null, 'act' => 'Pointprod|Pointorder'),
                    array('name' => lang('ds_voucher_price_manage'), 'op' => null, 'act' => 'Voucher'),
                    array('name' => lang('ds_bill_manage'), 'op' => null, 'act' => 'Bill'),
                    array('name' => lang('ds_activity_manage'), 'op' => null, 'act' => 'Vrbill'),
                    array('name' => lang('ds_mall_consult'), 'op' => null, 'act' => 'Mallconsult'),
                    array('name' => lang('ds_rechargecard'), 'op' => null, 'act' => 'Rechargecard'),
                    array('name' => lang('ds_delivery'), 'op' => null, 'act' => 'Delivery')
                )),
            array('name' => lang('ds_stat'), 'child' => array(
                    array('name' => lang('ds_statgeneral'), 'op' => null, 'act' => 'Statgeneral'),
                    array('name' => lang('ds_statindustry'), 'op' => null, 'act' => 'Statindustry'),
                    array('name' => lang('ds_statmember'), 'op' => null, 'act' => 'Statmember'),
                    array('name' => lang('ds_statstore'), 'op' => null, 'act' => 'Statstore'),
                    array('name' => lang('ds_stattrade'), 'op' => null, 'act' => 'Stattrade'),
                    array('name' => lang('ds_statgoods'), 'op' => null, 'act' => 'Statgoods'),
                    array('name' => lang('ds_statmarketing'), 'op' => null, 'act' => 'Statmarketing'),
                    array('name' => lang('ds_stataftersale'), 'op' => null, 'act' => 'Stataftersale'),
                )),
        );

        return $_limit;
    }

}

?>
