<?php
namespace app\wap\controller;


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

    }

    // 获取消息
    
    /**
     * 查询账号 查询人物信息
     * @return [type] [description]
     */
    public function FriendSerch(){
        $friend_mobile = input('post.mobile');
        $Member = model('Member');
        // $friendInfo = $Member->
        if (!$friendInfo) output_error('没有此账号！');
        output_data($friendInfo);
    
    }

    /**
     * 添加好友 --申请   发送人消息 ，可直接设置备注
     * @return [type] [description]
     */
    public function SendFriendlyMessage(){

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
     * 创建群聊
     */
    public function MakeGroupChat(){

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