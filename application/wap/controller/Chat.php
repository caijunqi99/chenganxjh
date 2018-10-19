<?php
namespace app\wap\controller;

use Cloud\Core\RongCloud;

class Chat extends MobileMember
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 生成第三方token  融云
     * @return [type] [description]
     */
    public function GetRongCloudToken(){

        $RongCloud = new RongCloud();
        $this->member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        // 获取 Token 方法
        $result = $RongCloud->user()->getToken($this->member_info['member_id'], $this->member_info['member_mobile'], $this->member_info['avator']);
        $result = json_decode($result,TRUE);
        if ($result['code']==200) {
            $CloudToken = $result['token'];
            $CloudUserId = $result['userId'];
            output_data(array(
                'CloudUserId' => $result['userId'],
                'CloudToken'  => $result['token']
            ));
        }else{
            output_error('token生成失败！');
        }
        
        

    }

    /**
     * 刷新Token
     */
    public function RefreshRongCloudToken(){
        $RongCloud = new RongCloud();
        $this->member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $result = $RongCloud->user()->refresh($this->member_info['member_id'], $this->member_info['member_mobile'], $this->member_info['avator']);
        $result = json_decode($result,TRUE);
        if ($result['code']==200) {
            output_data(array('state'=>TRUE));
        }else{
            output_error('token生成失败！');
        }

    }


    /**
     * 检查用户在线状态
     */
    public function CheckOnline(){        
        $userId = input('post.user_id');
        $RongCloud = new RongCloud();
        $result = $RongCloud->user()->checkOnline($userId);
        $result = json_decode($result,TRUE);
        output_data($result);
    }

    /**
     * 添加用户到黑名单方法（每秒钟限 100 次） 
     */
    public function AddBlacklist(){
        $userId = input('post.user_id');
        $RongCloud = new RongCloud();
        $result = $RongCloud->user()->addBlacklist($this->member_info['member_id'],$userId);
        $result = json_decode($result,TRUE);
        output_data($result);
    }

    /**
     * 获取某用户的黑名单列表方法（每秒钟限 100 次）
     */
    public function QueryBlacklist(){
        $userId = input('post.user_id');
        if (!$userId) $userId = $this->member_info['member_id'];
        $RongCloud = new RongCloud();
        $result = $RongCloud->user()->queryBlacklist($userId);
        $result = json_decode($result,TRUE);
        output_data($result);

    }

    /**
     * 从黑名单中移除用户方法（每秒钟限 100 次） 
     */
    public function RemoveBlacklist(){
        $userId = input('post.user_id');
        $RongCloud = new RongCloud();
        $result = $RongCloud->user()->removeBlacklist($this->member_info['member_id'],$userId);
        $result = json_decode($result,TRUE);
        output_data($result);

    }
    
    /**
     * 查询账号 查询人物信息
     * @return [type] [description]
     */
    public function FriendSerch(){
        $friend_mobile = input('post.mobile');
        $Member = model('Member');
        if(!$friend_mobile)output_error('账号不能为空！');
        $friendInfo = $Member->getMemberInfo(array('member_mobile'=>$friend_mobile));
        if (!$friendInfo) output_error('没有此账号！');
        $Area = model('Area');
        $areas=!empty($friendInfo['member_areaid'])?$Area->areaName($friendInfo['member_areaid']):$Area->areaName($friendInfo['member_provinceid']);
        $output = array(
            'member_id' => $friendInfo['member_id'],
            'member_name' => $friendInfo['member_name'],
            'mobile' => $friendInfo['member_mobile'],
            'avatar' => getMemberAvatarForID($friendInfo['member_id']),
            'area' =>empty($areas)?'':$areas,
        );
        output_data($output);
    
    }

    /**
     * 添加好友 --申请   发送人消息 ，可直接设置备注
     * @return [type] [description]
     */
    public function SendFriendlyMessage(){
        $friend_mobile = input('post.mobile');
        $apply_remark = input('post.apply_remark');
        $friend_remark = input('post.friend_remark');
        $Member = model('Member');
        if(!$friend_mobile)output_error('账号不能为空！');
        $friendInfo = $Member->getMemberInfo(array('member_mobile'=>$friend_mobile));
        if (!$friendInfo) output_error('没有此账号！');
        $Friendly = model('Friendly');
        //我是否发送过好友申请
        $myexits = array(
            'member_id' => $this->member_info['member_id'] ,
            'friend_id' => $friendInfo['member_id'] ,
        );
        $myexits = $Friendly->getOne($myexits);

        if ($myexits) {
            if($myexits['relation_state'] == 1)output_error('已发送好友申请!');
            if($myexits['relation_state'] == 2)output_error('双方已是好友关系!');
            if($myexits['relation_state'] == 3)output_error('对方已忽略申请!');
        }
        //查询的朋友是否给我发送过好友申请
        $frexits = array(
            'member_id' => $friendInfo['member_id'] ,
            'friend_id' => $this->member_info['member_id'] ,
        );
        $frexits = $Friendly->getOne($frexits);
        if($frexits){
            if($myexits['relation_state'] == 2)output_error('双方已是好友关系!');
        }
        output_data($frexits);
        
        //
        output_data($msgexits);
        $apply = array(
            'member_id' => $this->member_info['member_id'] ,
            'friend_id' => $friendInfo['member_id'] ,
            'relation_state' =>  1,
            'apply_remark' => !empty($apply_remark)?$apply_remark:'用户'.$this->member_info['member_name'].'申请成为你的好友',
            'friend_remark' =>  !empty($friend_remark)?$friend_remark:$this->member_info['member_name'],
            'creat_time' =>  time(),
            'from_type' =>  $this->member_info['member_id'] 
        );
        $applyResult = $Friendly->friendly_add($apply);
        if(!$applyResult)output_error('申请失败，请检查网络！');
        output_data(array('state'=>TRUE,'msg'=>'已发送好友申请!'));
    }

    /**
     * 新朋友列表
     */
    public function NewFriendsList(){

    }

    /**
     * 同意好友申请
     */
    public function AgreeMakeFriendly(){

    }

    /**
     * 忽略好友申请
     */
    public function NeglectFriendlyApply(){

    }

    /**
     * 修改好友备注
     */
    public function ModifyFriendNameRemarks(){

    }

    /**
     * 删除好友关系
     */
    public function DeleteFriendlyRelationship(){

    }


    //群聊 ***********************************************************************************************
    
    /**
     * 好友列表
     */
    public function FriendsList(){

    }

    /**
     * 创建群组方法（创建群组，并将用户加入该群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人，App 内的群组数量没有限制.）
     * 对于同一个聊天室，只存储该聊天室的 50 条最新消息，也就是说移动端用户进入聊天室时，最多能够拉取到最新的 50 条消息。
     * @param  userId:要加入群的用户 Id。（必传）
     * @param  groupId:创建群组 Id。（必传）
     * @param  groupName:群组 Id 对应的名称。（必传）
     */
    public function MakeGroupChat(){
        $input = input();
        $group_owner_id  = isset($input['owner_id'])?(!empty($input['owner_id'])?$input['owner_id']:$this->member_info['member_id']):$this->member_info['member_id'];
        $groupName = isset($input['groupName'])?$input['groupName']:$this->member_info['member_name'].'建立的群聊';
        $time = time();
        $create = array(
            'createTime' => $time,
            'lastTime' => $time,
            'groupState' => 1,
            'groupName' => $groupName,
            'sortNo' => $time,
            'user_creator_id' => $group_owner_id,
            'user_editor_id' => $group_owner_id,
            'group_owner_id' => $group_owner_id,
        );
        $Group = model('ChatGroup');
        //创建群聊
        $groupId = $Group->chatgroup_add($create);
        if (!$groupId) output_error('群组创建失败！');
        $members = $input['members'];
        $memberCount = count($members);
        $groupMembers = array();
        $memberIds = array();
        foreach ($variable as $k => $v) {
            $groupMembers[$k]=array(
                'group_id' => $groupId,
                'member_id' => $v['member_id'],
                'member_name' =>$v['member_name'],
                'member_avatar' =>$v['member_avatar'],
                'group_member_name' =>$v['member_name'],
                'join_time' => $time,
                'invite_member' => $group_owner_id,
            );
            $UserIds[] = $v['member_id'];
        }
        //添加群员
        $createMembers = $Group->chatgroup_addAll($groupMembers);
        if(!$createMembers)output_error('群员添加失败！');
        //设置群员数量
        $Group->chatgroup_set('member_count',$memberCount);
        //往融云发送建群请求
        $RongCloud = new RongCloud();
        $result = $RongCloud->group()->create($UserIds, $groupId, $groupName);
        $result = json_decode($result,TRUE);

        output_data($result);

    }

    /**
     * 邀请人加入群聊
     */
    public function GroupChatMemberInvite(){

    }

    /**
     * 同意加入群聊
     */
    public function AgreeJoinGroupChat(){

    }

    /**
     * 群聊列表
     */
    public function GroupChatList(){

    }

    /**
     * 群聊用户列表
     */
    public function GroupChatMemberList(){

    }

    /**
     * 修改群名称
     */
    public function ModifyGroupChatName(){

    }

    /**
     * 删除群员
     */
    public function DeleteGroupMember(){

    }


    /**
     * 退出群聊
     */
    public function LeaveGroupChat(){

    }

    





}                  