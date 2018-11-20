<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/18
 * Time: 9:46
 */

namespace app\admin\controller;


use think\Lang;
use think\Validate;

class Notice extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH.'admin/lang/zh-cn/notice.lang.php');
    }


    
    public function noticeManage(){
        echo 1111;
    }

    /**
     * 会员通知
     */
    public function notice(){
        //提交
        if (request()->isPost()){
            $content = trim(input('param.content1'));//信息内容
            $send_type = intval(input('param.send_type'));
            //验证
            $obj_validate= new Validate();
            switch ($send_type){
                //指定会员
                case 1:
                    $data=[
                        "user_name"=>input("param.user_name")
                    ];
                      $rule=[
                          ['user_name','require',lang('notice_index_member_list_null')]
                      ];
                    $error=$obj_validate->check($data,$rule);
                    if (!$error) {
                        $this->error($obj_validate->getError());
                    }
                    break;
                //全部会员
                case 2:
                    break;
            }
            $data=[
                "content1"=>$content
            ];
            $rule=[
                ['content1','require',('notice_index_content_null')]
            ];
            $error= $obj_validate->check($data,$rule);
            if (!$error){
                $this->error($obj_validate->getError());
            }else {
                //发送会员ID 数组
                $memberid_list = array();
                //整理发送列表
                //指定会员
                if ($send_type == 1){
                    $model_member = Model('member');
                    $tmp = explode("\n",input('param.user_name'));
                    if (!empty($tmp)){
                        foreach ($tmp as $k=>$v){
                            $tmp[$k] = trim($v);
                        }
                        //查询会员列表
                        $member_list = $model_member->getMemberList(array('member_name'=>array('in', $tmp)));
                        unset($membername_str);
                        if (!empty($member_list)){
                            foreach ($member_list as $k => $v){
                                $memberid_list[] = $v['member_id'];
                            }
                        }
                        unset($member_list);
                    }
                    unset($tmp);
                }
                if (empty($memberid_list) && $send_type != 2){
                    $this->error(lang('notice_index_member_error'));
                }
                //接收内容
                $array = array();
                $array['send_mode'] = 1;
                $array['user_name'] = $memberid_list;
                $array['content'] = $content;
                //添加短消息
                $model_message = model('message');
                $insert_arr = array();
                $insert_arr['from_member_id'] = 0;
                if ($send_type == 2){
                    $insert_arr['member_id'] = 'all';
                } else {
                    $insert_arr['member_id'] = ",".implode(',',$memberid_list).",";
                }
                $insert_arr['msg_content'] = $content;
                $insert_arr['message_type'] = 1;
                $insert_arr['message_ismore'] = 1;
                $model_message->saveMessage($insert_arr);
                //跳转
                $this->log(lang('notice_index_send'),1);
                $this->success(lang('notice_index_send_succ'),'notice/notice');
            }
        }
        $this->setAdminCurItem('notice');
        return $this->fetch('notice_add');
    }
    protected function getAdminItemList()
    {
        $menu_array=array(
            array(
                'name'=>'noticeManage','text'=>'公告管理','url'=>url('Notice/noticeManage')
            ),
            array(
                'name'=>'notice','text'=>'发送通知','url'=>url('Notice/notice')
            )
        );
        return $menu_array;
    }
}