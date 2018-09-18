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
//            dump($this->permission);exit;
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
//        halt($op);
//        halt($this->permission);
        if (empty($this->permission)){
            $gadmin = db('gadmin')->where(array('gid'=>$this->admin_info['admin_gid']))->find();
            $permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
            $this->permission = $permission = explode('|',$permission);
        }else{
            $permission = $this->permission;
        }
//        halt($this->permission);
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
//        halt(array_values($this->permission));
        foreach ($menu as $k => $v) {
            foreach ($v['children'] as $ck => $cv) {
                $tmp = explode(',', $cv['args']);
                //以下几项不需要验证
                $except = array('Index', 'Dashboard', 'Login','');
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
//        halt($menu);
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

        //获取父级所有栏目
        $menu = db('perms')->where(array('pid'=>0))->select();
        if(!empty($menu)){
            foreach($menu as $key=>$val){
                $menu[$key]['text'] = lang($val['text']);
                //获取子目录
                $menu[$key]['children'] = db('perms')->where(array('pid'=>$val['permid']))->select();
                if(!empty($menu[$key]['children'])){
                    foreach($menu[$key]['children'] as $k=>$v){
                        $menu[$key]['children'][$k]['text'] = lang($v['text']);
                    }
                }
                $menu[$key]['children'] = array_column($menu[$key]['children'],NULL,'name');
            }
        }
        $menu = array_column($menu,NULL,'name');
         return $menu;
    }

    /*
     * 权限选择列表
     */
    function limitList() {
        if ($this->admin_info['admin_is_super'] == 1) {
            //获取父级所有栏目
            $_limit = db('perms')->field('permid,name,text')->where("pid=0  AND permid != 1")->select();
            if(!empty($_limit)){
                foreach($_limit as $key=>$val){
                    $_limit[$key]['text'] = lang($val['text']);
                    //获取子目录
                    $_limit[$key]['child'] = db('perms')->field('permid,text,name,action')->where("pid='".$val['permid']."'")->select();
//                halt($_limit);
                    if(!empty($_limit[$key]['child'])){
                        foreach($_limit[$key]['child'] as $k=>$v){
                            $_limit[$key]['child'][$k]['text'] = lang($v['text']);
                            $_limit[$key]['child'][$k]['op'] = null;
                            $_limit[$key]['child'][$k]['act'] = ucfirst($v['name']);
                            $_limit[$key]['child'][$k]['action'] = explode(',',$_limit[$key]['child'][$k]['action']);
                            if(!empty($_limit[$key]['child'][$k]['action'])){
                                $actions= db('actions')->where("actid in ($v[action])")->select();
                                $array = array();
                                foreach ($actions as $kk=>$vv){
                                    $array['id']=$vv['actid'];
                                    $array['actname']=$this->get_action($vv['actname']);
                                    $_limit[$key]['child'][$k]['action'][$kk] = $array;
                                }
                            }
                        }
                    }
                }
            }
        }else{
            //根据角色id获取栏目权限
            $ginfo = db('gadmin')->where('gid', $this->admin_info['admin_gid'])->find();
            //解析已有权限
            $hlimit = decrypt($ginfo['limits'], MD5_KEY . md5($ginfo['gname']));
            $nlimit = decrypt($ginfo['nav'], MD5_KEY . md5($ginfo['gname']));
            $ginfo['limits'] = explode('|', $hlimit);
            $ginfo['nav'] = explode('|', $nlimit);
            $nav = "'" . join("','", $ginfo['nav']) . "'"; //大类
            $limits = "'" . join("','", $ginfo['limits']) . "'";//小类
//            halt($limits);
            //获取父级所有栏目
            $_limit = db('perms')->field('permid,name,text')->where("pid=0 AND name IN ($nav) AND permid != 1")->select();
//            halt($_limit);
            if(!empty($_limit)){
                foreach($_limit as $key=>$val){
                    $_limit[$key]['text'] = lang($val['text']);
                    //获取子目录
                    $_limit[$key]['child'] = db('perms')->field('permid,text,name,action')->where("pid='".$val['permid']."' AND name IN ($limits)")->select();
//                halt($_limit);
                    if(!empty($_limit[$key]['child'])){
                        foreach($_limit[$key]['child'] as $k=>$v){
                            $_limit[$key]['child'][$k]['text'] = lang($v['text']);
                            $_limit[$key]['child'][$k]['op'] = null;
                            $_limit[$key]['child'][$k]['act'] = ucfirst($v['name']);
                            $_limit[$key]['child'][$k]['action'] = explode(',',$_limit[$key]['child'][$k]['action']);
                            if(!empty($_limit[$key]['child'][$k]['action'])){
                                $actions= db('actions')->where("actid in ($v[action])")->select();
                                $array = array();
                                foreach ($actions as $kk=>$vv){
                                    $array['id']=$vv['actid'];
                                    $array['actname']=$this->get_action($vv['actname']);
                                    $_limit[$key]['child'][$k]['action'][$kk] = $array;
                                }
                            }
                        }
                    }
                }
            }
        }

//        halt($_limit);
       /* $_limit = array(
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
        );*/

        return $_limit;
    }


    /**
     * 获取后台操作权限
     * @param action $in
     */
    function get_action($in){
        //$s=array();
        switch ($in) {
            case 'Insert'://1
                return '增加';
                break;
            case 'Delete'://2
                return '删除';
                break;
            case 'Update'://3
                return '修改';
                break;
            case 'Select'://4
                return '浏览';
                break;

        }
    }


    /**
     * @desc 根据当前用户角色和子目录id 获取该角色对此子目录的权限
     * @author langzhiyao
     * @time 2018/09/18
     */
    function get_role_perms($roleid,$perm_id){
            $result = db('roleperms')->field('action')->where(" roleid=$roleid AND permsid=$perm_id")->find();
            return $result;
    }


}

?>
