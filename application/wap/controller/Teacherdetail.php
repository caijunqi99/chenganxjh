<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Teacherdetail extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    //教孩视频详情页
    public function detail(){
        $teachchild = model('Teachchild');
        $tid = intval(input('post.t_id'));
        if(empty($tid)){
            output_error('t_id参数有误');
        }
        $result[]['data'] = $teachchild->getTeachchildInfo(array('t_id'=>$tid));
        $conditions = array();
        $conditions['t_audit'] = 3;
        $conditions['t_del'] = 1;
        $conditions['t_type'] = $result[0]['data']['t_type'];
        $conditions['t_id'] = array('neq',$result[0]['data']['t_id']);
        $result[]['lists'] = $teachchild->getTeachchildList($conditions,'t_id,t_url,t_videoimg,t_title,t_profile,t_author', '' ,'t_maketime desc',4);
        if($result) {
            output_data($result);
        }else{
            output_error('无此视频');
        }
    }

    //判断是否购买 是否收藏
    public function decide($member_id,$video_id){
        //是否收藏
        $collectinfo = db("membercollect")->where(array('member_id'=>$member_id,'collect_id'=>$video_id,'type_id'=>1,'isdel'=>1))->find();
        if(!empty($collectinfo)){
            $data['collect'] = 1;
        }else{
            $data['collect'] = 0;
        }
        //是否购买
        $orderinfo = db('packagesorderteach')->where(array('buyer_id'=>$member_id,'order_tid'=>$video_id,'order_state'=>20))->order('order_id desc')->limit(1)->find();

        if(!empty($orderinfo)){
            if($orderinfo['order_dieline']>time()){
                $data['buy'] = 1;
            }else{
                $data['buy'] = 0;
            }
        }else{
            $data['buy'] = 0;
        }
        return $data;
    }

}