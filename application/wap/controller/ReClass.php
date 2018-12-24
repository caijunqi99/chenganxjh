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
        if($begintime<$time){
            $begintime='';
        }
        if($endtime<$time){
            $endtime='';
        }
        $id=$id.",";
        $vlink = new Vomont();
        $res= $vlink->SetLogin();
        $accountid=$res['accountid'];
        $res=$vlink->Videotape($accountid,$id,$begintime,$endtime);
        foreach($res['videos'] as $k=> $v){
            $res['videos'][$k]['begin']=date('Y-m-d H:i',$v['begintime']);
            $res['videos'][$k]['end']=date('Y-m-d H:i',$v['endtime']);
        }
        output_data($res['videos']);
    }

    //重温课堂购买片段页
    public function fragment(){

    }


    //重温课堂购买套餐页
    public function package(){

    }
}