<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:32
 */

namespace app\mobile\controller;

class Membersnsfriend extends MobileMember {

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 查询会员
     */
    public function member_list() {
        $member_list = array();
        $model_member = Model('member');
        $condition = array();
        $condition['member_state'] = '1';
        $condition['member_id'] = array('neq', $this->member_info['member_id']);
        $condition['member_name'] = array('like', '%' . trim($_POST['m_name']) . '%'); //会员名称
        $list = $model_member->getMemberList($condition, 'member_id,member_name,member_truename,member_avatar', $this->pagesize);
        if (!empty($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $member = array();
                $member['u_id'] = $v['member_id'];
                $member['u_name'] = $v['member_name'];
                $member['truename'] = $v['member_truename'];
                $member['avatar'] = getMemberAvatar($v['member_avatar']);
                $member_list[] = $member;
            }
        }
        output_data(array('member_list' => $member_list), mobile_page($model_member->page_info));
    }

    /**
     * 好友列表
     */
    public function friend_list() {
        $model_chat = Model('webchat');
        $member_id = $this->member_info['member_id'];
        $friend_list = $model_chat->getFriendList(array('friend_frommid' => $member_id), $this->pagesize);
        output_data(array('friend_list' => $friend_list), mobile_page($model_chat->page_info));
    }

    /**
     * 添加好友
     */
    public function friend_add() {
        $member_info = array();
        $self_info = $this->member_info;
        $m_id = intval($_POST['m_id']);
        if ($m_id < 1 || $m_id == $self_info['member_id']) {
            output_error('参数错误');
        }
        //验证会员信息
        $model_member = Model('member');
        $condition = array();
        $condition['member_state'] = '1';
        $condition['member_id'] = $m_id;
        $member_info = $model_member->getMemberInfo($condition);
        if (empty($member_info)) {//验证会员信息
            output_error('会员信息错误');
        }
        $model_snsfriend = Model('snsfriend');
        $count = $model_snsfriend->countFriend(array(
            'friend_tomid' => $m_id, 'friend_frommid' => $self_info['member_id']
        ));
        if ($count > 0) {//判断是否已经存在好友记录
            output_error('已经是好友了');
        }
        $insert_arr = array();
        $insert_arr['friend_frommid'] = $self_info['member_id'];
        $insert_arr['friend_frommname'] = $self_info['member_name'];
        $insert_arr['friend_frommavatar'] = $self_info['member_avatar'];
        $insert_arr['friend_tomid'] = $member_info['member_id'];
        $insert_arr['friend_tomname'] = $member_info['member_name'];
        $insert_arr['friend_tomavatar'] = $member_info['member_avatar'];
        $insert_arr['friend_addtime'] = time();
        $friend_info = $model_snsfriend->getFriendRow(array(
            'friend_frommid' => $m_id,
            'friend_tomid' => $self_info['member_id']
        ));
        if (empty($friend_info)) {
            $insert_arr['friend_followstate'] = '1'; //单方关注
        } else {
            $insert_arr['friend_followstate'] = '2'; //双方关注
        }
        $result = $model_snsfriend->addFriend($insert_arr);
        if ($result) {
            if (!empty($friend_info)) {//更新对方关注状态
                $model_snsfriend->editFriend(array('friend_followstate' => '2'), array('friend_id' => $friend_info['friend_id']));
            }
            output_data('1');
        } else {
            output_error('添加好友失败');
        }
    }

    /**
     * 删除好友
     */
    public function friend_del() {
        $m_id = intval($_POST['m_id']);
        if ($m_id <= 0) {
            output_error('参数错误');
        }
        $model_snsfriend = Model('snsfriend');
        $condition = array();
        $condition['friend_tomid'] = $m_id;
        $condition['friend_frommid'] = $this->member_info['member_id'];
        $result = $model_snsfriend->delFriend($condition);
        if ($result) {
            //更新对方的关注状态
            $model_snsfriend->editFriend(array('friend_followstate' => '1'), array(
                'friend_frommid' => $m_id, 'friend_tomid' => $this->member_info['member_id']
            ));
            output_data('1');
        } else {
            output_error('删除好友失败');
        }
    }

}