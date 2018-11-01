<?php

namespace app\school\controller;


use think\Lang;

class Snsmalbum extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'school/lang/zh-cn/snsmalbum.lang.php');
    }

    /**
     * 相册设置
     */
    public function setting()
    {
        $model_setting = Model('config');
        if (request()->isPost()) {
            //构造更新数据数组
            $update_array = array();
            $update_array['malbum_max_sum'] = intval($_POST['malbum_max_sum']);
            $result = $model_setting->updateConfig($update_array);
            if ($result === true) {
                $this->error(lang('ds_common_save_succ'));
            }
            else {
                $this->error(lang('ds_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListConfig();
        $this->assign('list_setting', $list_setting);
        $this->setAdminCurItem('setting');
        return $this->fetch();
    }

    /**
     * 相册列表
     */
    public function index()
    {

        // 相册总数量
        $where = array();
        if (input('param.class_name') != '') {
            $where['ac_name'] = array('like', '%' . trim(input('param.class_name')) . '%');
        }
        if (input('param.user_name') != '') {
            $where['member_name'] = array('like', '%' . trim(input('param.user_name')) . '%');
        }
        $ac_lists = db('snsalbumclass')->alias('a')->field('a.*,m.member_name')->join('__MEMBER__ m', 'a.member_id=m.member_id', 'LEFT')->where($where)->paginate(10,false,['query' => request()->param()]);
        if (!empty($ac_lists)) {
            $ac_list= $ac_lists->items();
            $acid_array = array();
            foreach ($ac_list as $val) {
                $acid_array[] = $val['ac_id'];
            }
            // 相册中商品数量
            $ap_count = db('snsalbumpic')->field('count(ap_id) as count,ac_id')->where(array('ac_id' => array(
                'in', $acid_array)))->group('ac_id')->select();
            $ap_count = array_under_reset($ap_count, 'ac_id', 1);
            foreach ($ac_list as $key => $val) {
                if (isset($ap_count[$val['ac_id']])) {
                    $ac_list[$key]['count'] = $ap_count[$val['ac_id']]['count'];
                }
                else {
                    $ac_list[$key]['count'] = 0;
                }
            }
        }
        $this->assign('showpage', $ac_lists->render());
        $this->assign('ac_list', $ac_list);
        $this->setAdminCurItem('index');
        return $this->fetch('index');
    }

    /**
     * 图片列表
     */
    public function pic_list()
    {
       
        // 删除图片
        if (request()->isPost()) {
            $where = array('ap_id' => array('in', $_POST['id']));
            $ap_list =db('snsalbumpic')->where($where)->select();
            if (empty($ap_list)) {
                $this->error(lang('snsalbum_choose_need_del_img'));
            }
            foreach ($ap_list as $val) {
                @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $val['member_id'] . DS . $val['ap_cover']);
                @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $val['member_id'] . DS . str_ireplace('.', '_240.', $val['ap_cover']));
                @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $val['member_id'] . DS . str_ireplace('.', '_1280.', $val['ap_cover']));
            }
           db('snsalbumpic')->where($where)->delete();
            $this->log(lang('ds_del').lang('ds_member_album_manage') . '[ID:' . implode(',', $_POST['id']) . ']', 1);
            $this->error(lang('ds_common_del_succ'));
        }
        $id = intval(input('param.id'));
        if ($id <= 0) {
            $this->error(lang('param_error'));
        }
        $where = array();
        $where['ac_id'] = $id;
        if (input('param.pic_name') != '') {
            $where['ap_name|ap_cover'] = array('like', '%' . input('param.pic_name') . '%');
        }
        $pic_list =db('snsalbumpic')->where($where)->paginate(10,false,['query' => request()->param()]);
        $this->assign('id', $id);
        $this->assign('showpage', $pic_list->render());
        $this->assign('pic_list', $pic_list);
        $this->setAdminCurItem('pic_list');
        return $this->fetch();
    }

    /**
     * 删除图片
     */
    public function del_picOp()
    {
        $id = intval(input('param.id'));
        if ($id <= 0) {
            $this->error(lang('param_error'));
        }

        $ap_info =db('snsalbumpic')->find($id);
        if (!empty($ap_info)) {
            @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $ap_info['member_id'] . DS . $ap_info['ap_cover']);
            @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $ap_info['member_id'] . DS . str_ireplace('.', '_240.', $ap_info['ap_cover']));
            @unlink(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $ap_info['member_id'] . DS . str_ireplace('.', '_1280.', $ap_info['ap_cover']));
           db('snsalbumpic')->delete($id);
        }
        $this->error(lang('ds_common_del_succ'));
    }

    protected function getAdminItemList($curitem = '')
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('snsalbum_class_list'), 'url' => url('Snsmalbum/index')
            ), array(
                'name' => 'setting', 'text' => lang('snsalbum_album_setting'), 'url' => url('Snsmalbum/setting')
            ),
        );
        if(request()->action()=='pic_list'){
            $menu_array[]=array(
                'name' => 'pic_list', 'text' => lang('snsalbum_pic_list'), 'url' => url('Snsmalbum/pic_list')
            );
        }
        return $menu_array;
    }
}