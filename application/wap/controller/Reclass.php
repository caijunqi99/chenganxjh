<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;
use think\Model;
use vomont\Vomont;


class Reclass extends MobileMall
{

    //重温课堂首页
    public function index(){
        $id = intval(input('post.id'));
        $begintime=intval(input('post.begintime'));
        $endtime=intval(input('post.endtime'));
        $time=strtotime("-4 month");
        $member_id=intval(input('post.member_id'));

//        $begintime=strtotime(date('Y-m-d'.'07:00:00',time()));
//        $endtime=strtotime(date('Y-m-d'.'17:00:00',time()));
        if((!empty($begintime) && $begintime<$time) || empty($begintime)){
            $begintime='';
        }
        if((!empty($endtime) && $endtime<$time) || empty($endtime)){
            $endtime='';
        }
        $id=$id.",";
        $vlink = new Vomont();
        $res= $vlink->SetLogin();
        $accountid=$res['accountid'];
        $res=$vlink->Videotape($accountid,$id,$begintime,$endtime);
        $video = $res['videos'];
        if(!empty($video)){
            //判断是否购买套餐并且套餐没有过期
            $is_buy_tc= db('packagetime')->where('member_id="'.$member_id.'" AND pkg_type=2')->find();
            if(!empty($is_buy_tc) && $is_buy_tc['end_time'] <time()){
                foreach($video as $k=> $v){
                    //按日期分组
                    $video[$k]['date'] = date("Y-m-d",$v['begintime']);
                    $video[$k]['begin']=date('Y-m-d H:i',$v['begintime']);
                    $video[$k]['end']=date('Y-m-d H:i',$v['endtime']);
                    $video[$k]['is_buy'] = 1;
                }
            }else{
                foreach($video as $k=> $v){
                    //按日期分组
                    $video[$k]['date'] = date("Y-m-d",$v['begintime']);
                    $video[$k]['begin']=date('Y-m-d H:i',$v['begintime']);
                    $video[$k]['end']=date('Y-m-d H:i',$v['endtime']);
                    //判断是否购买片段
                    $video[$k]['is_buy'] = 2;
                }
            }
            foreach($video as $key=>$item){
                $data[$item['date']][] = $item;
                $last_time = $item['begintime'];
            }
            foreach ($data as $ke=>$va){
                    $rr[$ke]['date'] = $ke;
                    $rr[$ke]['list']=$va;
            }
            $rr = array_reverse(array_values($rr));
            $datas = !empty($rr) ? $rr : $rr;
            $res=array();
            $res['content'] = $datas;
            $res['time'] = !empty($last_time)?$last_time:"";

        }
        output_data($res);
    }

    //重温课堂购买片段页
    public function fragment(){

    }
}