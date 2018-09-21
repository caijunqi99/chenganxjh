<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Member extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/member.lang.php');
    }

    public function member() {
        $model_member = Model('member');

        //会员级别
        $member_grade = $model_member->getMemberGradeArr();
        $search_field_value = input('search_field_value');
        $search_field_name = input('search_field_name');
        $condition = array();
        if ($search_field_value != '') {
            switch ($search_field_name) {
                case 'member_name':
                    $condition['member_name'] = array('like', '%' . trim($search_field_value) . '%');
                    break;
                case 'member_email':
                    $condition['member_email'] = array('like', '%' . trim($search_field_value) . '%');
                    break;
                case 'member_mobile':
                    $condition['member_mobile'] = array('like', '%' . trim($search_field_value) . '%');
                    break;
                case 'member_truename':
                    $condition['member_truename'] = array('like', '%' . trim($search_field_value) . '%');
                    break;
            }
        }
        $search_state = input('search_state');
        switch ($search_state) {
            case 'no_informallow':
                $condition['inform_allow'] = '2';
                break;
            case 'no_isbuy':
                $condition['is_buy'] = '0';
                break;
            case 'no_isallowtalk':
                $condition['is_allowtalk'] = '0';
                break;
            case 'no_memberstate':
                $condition['member_state'] = '0';
                break;
        }

        $search_identity = input('search_identity');
        switch ($search_identity) {
            case 1:
                $condition['member_identity'] = 1;
                break;
            case 2:
                $condition['member_identity'] = 2;
                break;
            case 3:
                $condition['member_identity'] = 3;
                break;
        }
        //会员等级
        $search_grade = input('search_grade');
        if (!empty($search_grade) && $member_grade) {
            $condition['member_exppoints'] = array(array('egt', $member_grade[$search_grade]['exppoints']), array('lt', $member_grade[$search_grade + 1]['exppoints']), 'and');
        }
        //排序
        $order = trim(input('get.search_sort'));
        if (empty($order)) {
            $order = 'member_id desc';
        }
        $member_list = $model_member->getMemberList($condition, '*', 10, $order);
        //整理会员信息
        if (is_array($member_list)) {
            foreach ($member_list as $k => $v) {
                $member_list[$k]['member_add_time'] = $v['member_add_time'] ? date('Y-m-d H:i:s', $v['member_add_time']) : '';
                $member_list[$k]['member_login_time'] = $v['member_login_time'] ? date('Y-m-d H:i:s', $v['member_login_time']) : '';
                $member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade)) ? $t['level_name'] : '';
            }
        }
        $this->assign('member_grade', $member_grade);
        $this->assign('search_sort', $order);
        $this->assign('search_field_name', trim($search_field_name));
        $this->assign('search_field_value', trim($search_field_value));
        $this->assign('member_list', $member_list);
        $this->assign('page', $model_member->page_info->render());

        $this->setAdminCurItem('member');
        return $this->fetch();
    }

    public function add() {

        if (!request()->isPost()) {
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            //需要完善地方 1.对录入数据进行判断  2.对判断用户名是否存在
            $model_member = Model('member');
            $data = array(
                'member_name'      => input('post.member_name'),
                'member_password'  => input('post.member_password'),
                'member_email'     => input('post.member_email'),
                'member_truename'  => input('post.member_truename'),
                'member_sex'       => input('post.member_sex'),
                'member_identity'  => input('post.member_identity'),
                'member_nickname'  => input('post.member_nickname'),
                // 'member_qq'     => input('post.member_qq'),
                // 'member_ww'     => input('post.member_ww'),
                'member_add_time'  => TIMESTAMP,
                'member_login_num' => 0,
                'inform_allow'     => 1, //默认允许举报商品
            );
            //验证数据  BEGIN
            $rule = [
                ['member_name', 'require|length:3,12|unique:member', '用户名必填|用户名长度在3到12位|用户名已存在'],
                ['member_password', 'require', '密码为必填'],
                ['member_email', 'email', '邮箱格式错误']
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($data);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END
            $result = $model_member->addMember($data);
            if ($result) {
                $this->success(lang('member_add_succ'), 'Member/member');
            } else {
                $this->error(lang('member_add_fail'));
            }
        }
    }

    public function edit() {
        //注：pathinfo地址参数不能通过get方法获取，查看“获取PARAM变量”
        $member_id = input('param.member_id');
        if (empty($member_id)) {
            $this->error(lang('param_error'));
        }
        $model_member = Model('member');
        if (!request()->isPost()) {
            $condition['member_id'] = $member_id;
            $member_array = $model_member->getMemberInfo($condition);
            $this->assign('member_array', $member_array);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {

            $data = array(
                'member_email'       => input('post.member_email'),
                'member_truename'    => input('post.member_truename'),
                'member_sex'         => input('post.member_sex'),
                // 'member_qq'       => input('post.member_qq'),
                // 'member_ww'       => input('post.member_ww'),
                'inform_allow'       => input('post.inform_allow'),
                'is_buy'             => input('post.isbuy'),
                'is_allowtalk'       => input('post.allowtalk'),
                'member_state'       => input('post.memberstate'),
                'member_cityid'      => input('post.city_id'),
                'member_provinceid'  => input('post.province_id'),
                'member_areainfo'    => input('post.area_info'),
                'member_areaid'      => input('post.area_id'),
                'member_mobile'      => input('post.member_mobile'),
                'member_email_bind'  => input('post.memberemailbind'),
                'member_mobile_bind' => input('post.membermobilebind'),
                'member_birthday'    => input('post.member_birthday'),
                'member_identity'    => input('post.member_identity'),
                'member_nickname'    => input('post.member_nickname'),

            );

            if (input('post.member_password')) {
                $data['member_password'] = md5(input('post.member_password'));
            }
            //验证数据  BEGIN
            $rule = [
                ['member_email', 'email', '邮箱格式错误']
            ];
            $validate = new Validate($rule);
            $validate_result = $validate->check($data);
            if (!$validate_result) {
                $this->error($validate->getError());
            }
            //验证数据  END
            $result = $model_member->editMember(array('member_id'=>intval($member_id)),$data);
            if ($result) {
                $this->success('编辑成功', 'Member/member');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    /**
     * ajax操作
     */
    public function ajax() {
        $branch = input('param.branch');

        switch ($branch) {
            /**
             * 验证会员是否重复
             */
            case 'check_user_name':
                $model_member = Model('member');
                $condition['member_name'] = input('param.member_name');
                $condition['member_id'] = array('neq', intval(input('get.member_id')));
                $list = $model_member->getMemberInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 验证邮件是否重复
             */
            case 'check_email':
                $model_member = Model('member');
                $condition['member_email'] = input('param.member_email');
                $condition['member_id'] = array('neq', intval(input('param.member_id')));
                $list = $model_member->getMemberInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**
     * 重要提示，删除会员 要先确定删除店铺,然后删除会员以及会员相关的数据表信息。这个后期需要完善。
     */
    public function drop() {
        //注：pathinfo地址参数不能通过get方法获取，查看“获取PARAM变量”
        $member_id = input('param.member_id');
        if (empty($member_id)) {
            $this->error(lang('param_error'));
        }
        $result = db('member')->delete($member_id);
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'Member/member');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'member',
                'text' => '管理',
                'url' => url('Admin/Member/member')
            ),
        );

        if (request()->action() == 'add' || request()->action() == 'member') {
            $menu_array[] = array(
                'name' => 'add',
                'text' => '新增',
                'url' => url('Admin/Member/add')
            );
        }
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Member/edit')
            );
        }
        return $menu_array;
    }

}

?>
