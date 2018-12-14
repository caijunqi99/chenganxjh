<?php

namespace app\admin\controller;

use think\Lang;

class Dashboard extends AdminControl {

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'admin/lang/zh-cn/dashboard.lang.php');
    }

    function index() {
        $this->welcome();
    }

    /*
     * 检查是否为最新版本
     */

    function version() {
        //当前版本
        // $curent_version = file_get_contents(APP_PATH . 'version.php');
        // //获取最新版本信息
        // $vaules = array(
        //     'domain'=>$_SERVER['HTTP_HOST'], 
        //     'version'=>$curent_version, 
        // );
        // $service_url = "http://service.csdeshang.com/index.php/Home/Version/checkDsmall.html?".http_build_query($vaules);
        // $service_info = @file_get_contents($service_url);
        // $version_message = json_decode($service_info);
        // $this->assign('version_message', $version_message);
    }

    function welcome() {
        $this->version();
        /**
         * 管理员信息
         */
        $model_admin = Model('admin');
        $tmp = $this->getAdminInfo();
        $condition['admin_id'] = $tmp['admin_id'];
        $admin_info = $model_admin->infoAdmin($condition);
        $admin_info['admin_login_time'] = date('Y-m-d H:i:s', ($admin_info['admin_login_time'] == '' ? time() : $admin_info['admin_login_time']));
        /**
         * 系统信息
         */
        $setup_date = config('setup_date');
        $statistics['os'] = PHP_OS;
        $statistics['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $statistics['php_version'] = PHP_VERSION;
        $statistics['sql_version'] = $this->_mysql_version();
        //$statistics['shop_version'] = $version;
        $statistics['setup_date'] = substr($setup_date, 0, 10);

        $statistics['domain'] = $_SERVER['HTTP_HOST'];
        $statistics['ip'] = GetHostByName($_SERVER['SERVER_NAME']);
        $statistics['zlib'] = function_exists('gzclose') ? 'YES' : 'NO'; //zlib
        $statistics['safe_mode'] = (boolean) ini_get('safe_mode') ? 'YES' : 'NO'; //safe_mode = Off
        $statistics['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $statistics['curl'] = function_exists('curl_init') ? 'YES' : 'NO';
        $statistics['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown';
        $statistics['max_ex_time'] = @ini_get("max_execution_time") . 's'; //脚本最大执行时间
        $statistics['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $statistics['memory_limit'] = ini_get('memory_limit');
        $statistics['version'] = file_get_contents(APP_PATH . 'version.php');
        if (function_exists("gd_info")) {
            $gd = gd_info();
            $statistics['gdinfo'] = $gd['GD Version'];
        } else {
            $statistics['gdinfo'] = "未知";
        }

        $this->assign('statistics', $statistics);
        $this->assign('admin_info', $admin_info);
        $this->setAdminCurItem('welcome');
        echo $this->fetch('welcome');
        exit;
    }

    private function _mysql_version() {
        $version = db()->query("select version() as ver");
        return $version[0]['ver'];
    }

    function aboutus() {
        $this->setAdminCurItem('aboutus');
        return $this->fetch();
    }

    /**
     * 统计
     */
    public function statistics() {
        $statistics = array();

        //今日开始时间点和结束时间点
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));

        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

        // 本周开始时间点
        $tmp_time = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - (date('w') == 0 ? 7 : date('w') - 1) * 24 * 60 * 60;

        $beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));

        $endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
        //本月开始时间点和结束时间点
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));

        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        /**
         * @desc 会员
         * @author langzhiyao
         */
        $model_member = Model('member');
        // 会员总数
        $statistics['member'] = $model_member->getMemberCount(array('member_identity'=>1));

        // 新增会员数
//        $statistics['week_add_member'] = $model_member->getMemberCount(array('member_add_time' => array('egt', $tmp_time)));
        // 预存款提现
//        $statistics['cashlist'] = Model('predeposit')->getPdCashCount(array('pdc_payment_state' => 0));
        //langzhiyao
        //当日新增
        $statistics['member_day'] = $model_member->getMemberCount(array('member_identity'=>1,'member_add_time' => array('elt', $endToday),'member_add_time' => array('egt', $beginToday)));
        //本月新增
        $statistics['member_month'] = $model_member->getMemberCount(array('member_identity'=>1,'member_add_time' => array('elt', $endThismonth),'member_add_time' => array('egt', $beginThismonth)));

        /**
         * @desc 教师
         * @author langzhiyao
         */
        // 总数
        $statistics['teacher'] = $model_member->getMemberCount(array('member_identity'=>2));
        //当日新增
        $statistics['teacher_day'] = $model_member->getMemberCount(array('member_identity'=>2,'member_add_time' => array('elt', $endToday),'member_add_time' => array('egt', $beginToday)));
        //本月新增
        $statistics['teacher_month'] = $model_member->getMemberCount(array('member_identity'=>2,'member_add_time' => array('elt', $endThismonth),'member_add_time' => array('egt', $beginThismonth)));

        /**
         * @desc 教师视频
         * @author langzhiyao
         */
        $model_teacherVideo = Model('teachchild');
        // 总数
        $statistics['teacherVideo'] = $model_teacherVideo->getVideoCount(array());
        //当日新增
        $statistics['teacherVideo_day'] = $model_teacherVideo->getVideoCount(array('t_maketime' => array('elt', $endToday),'t_maketime' => array('egt', $beginToday)));
        //本月新增
        $statistics['teacherVideo_month'] = $model_teacherVideo->getVideoCount(array('t_maketime' => array('elt', $endThismonth),'t_maketime' => array('egt', $beginThismonth)));

        /**
         * @desc 代理商
         * @author langzhiyao
         */
        $model_agent = Model('Company');
        // 总数
        $statistics['agent'] = $model_agent->getAgentCount(array());
        //当日新增
        $statistics['agent_day'] = $model_agent->getAgentCount(array('o_createtime' => array('elt', date('Y-m-d H:i:s',$endToday)),'o_createtime' => array('egt', date('Y-m-d H:i:s',$beginToday))));
        //本月新增
        $statistics['agent_month'] = $model_agent->getAgentCount(array('o_createtime' => array('elt', date('Y-m-d H:i:s',$endThismonth)),'o_createtime' => array('egt', date('Y-m-d H:i:s',$beginThismonth))));
        /**
         * @desc 学校
         * @author langzhiyao
         */
        $model_school = Model('School');
        // 总数
        $statistics['school'] = $model_school->getSchoolCount(array());
        //当日新增
        $statistics['school_day'] = $model_school->getSchoolCount(array('createtime' => array('elt', date('Y-m-d H:i:s',$endToday)),'createtime' => array('egt', date('Y-m-d H:i:s',$beginToday))));
        //本月新增
        $statistics['school_month'] = $model_school->getSchoolCount(array('createtime' => array('elt', date('Y-m-d H:i:s',$endThismonth)),'createtime' => array('egt', date('Y-m-d H:i:s',$beginThismonth))));
        /**
         * @desc 商城订单数量
         * @author langzhiyao
         */
        $model_order = Model('Order');
        // 总数
        $statistics['trade'] = $model_order->getOrderCount(array());
        //当日新增
        $statistics['trade_day'] = $model_order->getOrderCount(array('add_time' => array('elt',$endToday),'add_time' => array('egt',$beginToday)));
        //本月新增
        $statistics['trade_month'] = $model_order->getOrderCount(array('add_time' => array('elt', $endThismonth),'add_time' => array('egt', $beginThismonth)));
        /**
         * @desc 摄像头数量
         * @author langzhiyao
         */
        $model_camera = Model('Camera');
        // 总数
        $statistics['camera'] = $model_camera->getCameraCount(array());
        //当日新增
        $statistics['camera_day'] = $model_camera->getCameraCount(array('sq_time' => array('elt',$endToday),'sq_time' => array('egt',$beginToday)));
        //本月新增
        $statistics['camera_month'] = $model_camera->getCameraCount(array('sq_time' => array('elt', $endThismonth),'sq_time' => array('egt', $beginThismonth)));
        /**
         * @desc 机器人数量
         * @author langzhiyao
         */
        $model_robot = Model('Robot');
        // 总数
        $statistics['robot'] = $model_robot->getRobotCount(array());
        //当日新增
        $statistics['robot_day'] = $model_robot->getRobotCount(array('creattime' => array('elt',$endToday),'creattime' => array('egt',$beginToday)));
        //本月新增
        $statistics['robot_month'] = $model_robot->getRobotCount(array('creattime' => array('elt', $endThismonth),'creattime' => array('egt', $beginThismonth)));
        /**
         * @desc 蓝牙防丢数量
         * @author langzhiyao
         */
//        $model_blueTooth = Model('Bluetooth');
        // 总数
//        $statistics['blueTooth'] = $model_blueTooth->getBlueToothCount(array());
/*        //当日新增
        $statistics['blueTooth_day'] = $model_blueTooth->getBlueToothCount(array('add_time' => array('elt',$endToday),'add_time' => array('egt',$beginToday)));
        //本月新增
        $statistics['blueTooth_month'] = $model_blueTooth->getBlueToothCount(array('add_time' => array('elt', $endThismonth),'add_time' => array('egt', $beginThismonth)));*/
        /**
         * 店铺
        $model_store = Model('store');
        // 店铺总数
        $statistics['store'] = Model('store')->getStoreCount(array());
        // 店铺申请数
        $statistics['store_joinin'] = Model('storejoinin')->getStoreJoininCount(array('joinin_state' => array('in', array(10, 11))));
        //经营类目申请
        $statistics['store_bind_class_applay'] = Model('storebindclass')->getStoreBindClassCount(array('state' => 0));
        //店铺续签申请
        $statistics['store_reopen_applay'] = Model('storereopen')->getStoreReopenCount(array('re_state' => 1));
        // 即将到期
        $statistics['store_expire'] = $model_store->getStoreCount(array('store_state' => 1, 'store_end_time' => array('between', array(TIMESTAMP, TIMESTAMP + 864000))));
        // 已经到期
        $statistics['store_expired'] = $model_store->getStoreCount(array('store_state' => 1, 'store_end_time' => array('between', array(1, TIMESTAMP))));
         */
        /**
         * 商品
        $model_goods = Model('goods');
        // 商品总数
        $statistics['goods'] = $model_goods->getGoodsCommonCount(array());
        // 新增商品数
        $statistics['week_add_product'] = $model_goods->getGoodsCommonCount(array('goods_addtime' => array('egt', $tmp_time)));
        // 等待审核
        $statistics['product_verify'] = $model_goods->getGoodsCommonWaitVerifyCount(array());
        // 举报
        $statistics['inform_list'] = Model('inform')->getInformCount(array('inform_state' => 1));
        // 品牌申请
        $statistics['brand_apply'] = Model('brand')->getBrandCount(array('brand_apply' => '0'));
         */
        /**
         * 交易
        $model_order = Model('order');
        $model_refund_return = Model('refundreturn');
        $model_vr_refund = Model('vrrefund');
        $model_complain = Model('complain');
        // 订单总数
        $statistics['order'] = $model_order->getOrderCount(array());
        // 退款
        $statistics['refund'] = $model_refund_return->getRefundReturn(array('refund_type' => 1, 'refund_state' => 2));
        // 退货
        $statistics['return'] = $model_refund_return->getRefundReturn(array('refund_type' => 2, 'refund_state' => 2));
        // 虚拟订单退款
        $statistics['vr_refund'] = $model_vr_refund->getRefundCount(array('admin_state' => 1));
        // 投诉
        $statistics['complain_new_list'] = $model_complain->getComplainCount(array('complain_state' => 10));
        // 待仲裁
        $statistics['complain_handle_list'] = $model_complain->getComplainCount(array('complain_state' => 40));

         */
        /**
         * 运营
        // 抢购数量
        $statistics['groupbuy_verify_list'] = Model('groupbuy')->getGroupbuyCount(array('state' => 10));
        // 积分订单
        $statistics['points_order'] = db('pointsorder')->where(array('point_orderstate' => array('in', array(11, 20))))->count();
        //待审核账单
        $model_bill = Model('bill');
        $model_vr_bill = Model('vrbill');
        $condition = array();
        $condition['ob_state'] = BILL_STATE_STORE_COFIRM;
        $statistics['check_billno'] = $model_bill->getOrderBillCount($condition);
        $statistics['check_billno'] += $model_vr_bill->getOrderBillCount($condition);
        //待支付账单
        $condition = array();
        $condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
        $statistics['pay_billno'] = $model_bill->getOrderBillCount($condition);
        $statistics['pay_billno'] += $model_vr_bill->getOrderBillCount($condition);
        // 平台客服
        $statistics['mall_consult'] = Model('mallconsult')->getMallConsultCount(array('is_reply' => 0));
        // 服务站
        $statistics['delivery_point'] = Model('deliverypoint')->getDeliveryPointWaitVerifyCount(array());
         */
        /**
         * CMS
        if (config('cms_isuse')) {
            // 文章审核
            $statistics['cms_article_verify'] = Model('cmsarticle')->getCmsArticleCount(array('article_state' => 2));
            // 画报审核
            $statistics['cms_picture_verify'] = Model('cmspicture')->getCmsPictureCount(array('picture_state' => 2));
        }
         */
        /**
         * 圈子
        if (config('circle_isuse')) {
            $statistics['circle_verify'] = Model('circle')->getCircleUnverifiedCount();
        }
         */
        echo json_encode($statistics);
        exit;
    }

}

?>
