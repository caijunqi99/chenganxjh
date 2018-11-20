<?php

namespace app\admin\controller;

use think\Lang;

class Config extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/config.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    /**
     * @desc APP设置
     * @author langzhiyao
     * @time 20181018
     */
    public function index(){

        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_config = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $list_config['teacher_pay_scale'] = json_decode($list_config['teacher_pay_scale']);

//            halt($list_config['teacher_pay_scale']->province_agent);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('index');
            return $this->fetch();
        } else {

            $p_s_t = floatval(input('post.province_agent'))+floatval(input('post.city_agent'))+floatval(input('post.teacher'));
            if($p_s_t >= 100){
                //分配错误
                $this->error('教孩在线支付分成比例已超出100%，请重新分配');
            }
            $hq = 100-$p_s_t;
            $scale = array(
                'zb'=>$hq,
                'province_agent' =>floatval(input('post.province_agent')),
                'city_agent' =>floatval(input('post.city_agent')),
                'teacher' =>floatval(input('post.teacher')),
            );
            $update_array['teacher_children'] = input('post.teacher_children');
            $update_array['revisit_class'] = input('post.revisit_class');
            $update_array['teacher_children_video'] = input('post.teacher_children_video');
            $update_array['f_account_num'] = input('post.f_account_num');
            $update_array['teacher_pay_scale'] = json_encode($scale);
            $result = $model_config->updateConfig($update_array);
            if ($result) {
                dkcache('config');
                $this->log(lang('ds_edit').lang('web_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/index');
            }else{
                $this->log(lang('ds_edit').lang('web_set'),0);
            }
        }
    }

    /**
     * @desc 版本管理
     * @author langzhiyao
     * @time 20181120
     */
    public function version(){

        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_config = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);

//            halt($list_config);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('version');
            return $this->fetch();
        } else {
            $config = db('config')->find();
            $ios_version = explode('.',$config['version_ios_num']);
            $ios_num = $ios_version[0]*100+$ios_version[1]*10+$ios_version[2];

            $android_version = explode('.',$config['version_android_num']);
            $android_num = $android_version[0]*100+$android_version[1]*10+$android_version[2];

            $update_array['version_ios_num'] = input('post.version_ios_num');
            $update_array['version_android_num'] = input('post.version_android_num');
            //得到传过来的版本号
            $new_ios_version = explode('.',$update_array['version_ios_num']);
            $new_ios_num = $new_ios_version[0]*100+$new_ios_version[1]*10+$new_ios_version[2];

            $new_android_version = explode('.',$update_array['version_android_num']);
            $new_android_num = $new_android_version[0]*100+$new_android_version[1]*10+$new_android_version[2];
            //判断
            if($ios_num >$new_ios_num){
                $this->error('IOS版本号低于旧版本');
            }
            if($android_num > $new_android_num){
                $this->error('Android版本号低于旧版本');
            }
            $update_array['version_android_download'] = input('post.version_android_download');
            $update_array['version_ios_download'] = input('post.version_ios_download');
            $update_array['version_android_content'] = input('post.version_android_content');
            $update_array['version_ios_content'] = input('post.version_ios_content');

            $result = $model_config->updateConfig($update_array);
            if ($result) {
                dkcache('config');
                $this->log(lang('ds_edit').lang('web_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/index');
            }else{
                $this->log(lang('ds_edit').lang('web_set'),0);
            }
        }
    }

    /**
     * @return mixed
     * @desc 站点设置
     */
    public function base() {
        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_config = model('config');
        if (!request()->isPost()) {
            $list_config = rkcache('config', true);
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('base');
            return $this->fetch();
        } else {
            //上传文件保存路径
            $upload_file = BASE_UPLOAD_PATH . DS . ATTACH_COMMON;
            if (!empty($_FILES['site_logo']['name'])) {
                $file = request()->file('site_logo');
                $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_file, 'site_logo');
                if ($info) {
                    $upload['site_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_logo'])) {
                $update_array['site_logo'] = $upload['site_logo'];
            }
            if (!empty($_FILES['member_logo']['name'])) {
                $file = request()->file('member_logo');
                $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_file, 'member_logo');
                if ($info) {
                    $upload['member_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['member_logo'])) {
                $update_array['member_logo'] = $upload['member_logo'];
            }
            if (!empty($_FILES['seller_center_logo']['name'])) {
                $file = request()->file('seller_center_logo');
                $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_file, 'seller_center_logo');
                if ($info) {
                    $upload['seller_center_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['seller_center_logo'])) {
                $update_array['seller_center_logo'] = $upload['seller_center_logo'];
            }
            if (!empty($_FILES['site_mobile_logo']['name'])) {
                $file = request()->file('site_mobile_logo');
                $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_file, 'site_mobile_logo');
                if ($info) {
                    $upload['site_mobile_logo'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_mobile_logo'])) {
                $update_array['site_mobile_logo'] = $upload['site_mobile_logo'];
            }
            if (!empty($_FILES['site_logowx']['name'])) {
                $file = request()->file('site_logowx');
                $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_file, 'site_logowx');
                if ($info) {
                    $upload['site_logowx'] = $info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            if (!empty($upload['site_logowx'])) {
                $update_array['site_logowx'] = $upload['site_logowx'];
            }

            $update_array['site_name'] = input('post.site_name');
            $update_array['icp_number'] = input('post.icp_number');
            $update_array['site_phone'] = input('post.site_phone');
            $update_array['site_tel400'] = input('post.site_tel400');
            $update_array['site_email'] = input('post.site_email');
            $update_array['flow_static_code'] = input('post.flow_static_code');
            $update_array['site_state'] = input('post.site_state');
            $update_array['closed_reason'] = input('post.closed_reason');
            $update_array['hot_search'] = input('post.hot_search');
            $update_array['node_site_url'] = input('post.node_site_url');
            $result = $model_config->updateConfig($update_array);
            if ($result) {
                dkcache('config');
                $this->log(lang('ds_edit').lang('web_set'),1);
                $this->success(lang('ds_common_save_succ'), 'Config/base');
            }else{
                $this->log(lang('ds_edit').lang('web_set'),0);
            }
        }
    }

    /**
     * 防灌水设置
     */
    public function dump(){
        $model_config = model('config');
        if (!request()->isPost()) {
            $list_config = $model_config->getListConfig();
            $this->assign('list_config', $list_config);
            /* 设置卖家当前栏目 */
            $this->setAdminCurItem('dump');
            return $this->fetch();
        } else {
            $update_array = array();
            $update_array['guest_comment'] = $_POST['guest_comment'];
            $update_array['captcha_status_login'] = isset($_POST['captcha_status_login'])?'1':'';
            $update_array['captcha_status_register'] = isset($_POST['captcha_status_register'])?'1':'';
            $update_array['captcha_status_goodsqa'] = isset($_POST['captcha_status_goodsqa'])?'1':'';
            $result = $model_config->updateConfig($update_array);
            if ($result === true) {
                $this->log(lang('ds_edit').lang('dis_dump'), 1);
                $this->success(lang('ds_common_save_succ'));
            } else {
                $this->log(lang('ds_edit').lang('dis_dump'), 0);
                $this->error(lang('ds_common_save_fail'));
            }
        }
    }





    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '时长/分成/副账号设置',
                'url' => url('Admin/Config/index')
            ),
            array(
                'name' => 'version',
                'text' => '版本管理',
                'url' => url('Admin/Config/version')
            ),
//            array(
//                'name' => 'base',
//                'text' => '站点设置',
//                'url' => url('Admin/Config/base')
//            ),
//            array(
//                'name' => 'dump',
//                'text' => '防灌水设置',
//                'url' => url('Admin/Config/dump')
//            ),
        );
        return $menu_array;
    }

}
