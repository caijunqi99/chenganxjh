<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Common extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    public function navicon(){
        $type = intval(input('post.type',1));
        $navlist = db('navaicon')->field('icon_name,icon_1,icon_2,icon_3,link,link_type,group_type,group_name,icon_sign,icon_type')->where('type',$type)->select();
        foreach($navlist as $k=>&$v){
            $v['icon_2'] = getIconImage($v['icon_2'],'icon_2');
            $v['icon_3'] = getIconImage($v['icon_3'],'icon_3');
        }
        if ($type==1) {
            output_data($navlist);
        }else{
            $list = array();
            foreach ($navlist as $key => $v) {
                $list[$v['group_type']]['tab']=$v['group_name'];
                $list[$v['group_type']]['subTab'][]=$v;
            }
            output_data(array_values($list));
        }
        
    }

    /**
     * @desc 地区获取
     * @author langzhiyao
     * @time 20181002
     */
    public function address(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $where = ' area_parent_id = 0';
        $area = db('area')->field('area_id as code,area_name as name')->where($where)->select();
        if(!empty($area)){
            foreach($area as $key=>$value){
                $area[$key]['sub'] = db('area')->field('area_id as code,area_name as name')->where(' area_parent_id = "'.$value['code'].'"')->select();
                if(!empty($area[$key]['sub'])){
                    foreach($area[$key]['sub'] as $k=>$v){
                        $area[$key]['sub'][$k]['sub'] = db('area')->field('area_id as code,area_name as name')->where(' area_parent_id = "'.$v['code'].'"')->select();
                    }
                }
            }
        }


        output_data($area);

    }

    /**
     * @desc 行业获取
     * @author langzhiyao
     * @time 20181002
     */
    public function industry(){

        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $where = '1=1';
        $industry = db('industry')->field('name as title,id as value')->where($where)->select();
        output_data($industry);
    }

    /**
     * @desc 学校获取
     * @author langzhiyao
     * @time 20181002
     */
    public function school(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $province = intval(input('post.province'));
        $city = intval(input('post.city'));
        $area = intval(input('post.area'));
        $where = 'isdel =1 ';
        $school = '';
        if(!empty($province)){
            $where .= ' AND provinceid = "'.$province.'"';
            if(!empty($city)){
                $where .= ' AND cityid = "'.$city.'"';
                if(!empty($area)){
                    $where .= ' AND areaid = "'.$area.'"';
                }
            }
            $school = db('school')->field('name as title,schoolid as value')->where($where)->select();
        }

        output_data($school);

    }
    /**
     * @desc 获取年级接口
     * @author langzhiyao
     * @time 20181002
     */
    public function grade(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $school_id = intval(input('post.school_id'));
        if(empty($school_id)){
            output_error('缺少参数school_id');
        }
        $where = 'isdel =1 AND schoolid="'.$school_id.'"';

        $school = db('school')->field('schoolid,name,typeid')->where($where)->find();
        if(empty($school)){
           output_error('该学校不存在或已被删除，请联系管理员');
        }
        $arr = '';
        if(!empty($school['typeid'])){
            $type = explode(',',$school['typeid']);
            foreach($type as $key=>$value){
                $name =db('schooltype')->field('sc_id,sc_type')->where('sc_id  = "'.$value.'"')->find();
                $arr[$key]['value'] = $name['sc_id'];
                $arr[$key]['title'] = $name['sc_type'];
            }
        }

        output_data($arr);


    }

    /**
     * @desc 获取班级接口
     * @author langzhiyao
     * @time 20181002
     */
    public function classData(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $school_id = intval(input('post.school_id'));
        if(empty($school_id)){
            output_error('缺少参数school_id');
        }
        $grade_id = intval(input('post.grade_id'));
        if(empty($grade_id)){
            output_error('缺少参数grade_id');
        }

        $where = 'isdel =1 AND schoolid="'.$school_id.'"';

        $school = db('school')->field('schoolid,name,typeid')->where($where)->find();
        if(empty($school)){
            output_error('该学校不存在或已被删除，请联系管理员');
        }
        $grade =db('schooltype')->field('sc_id,sc_type')->where('sc_id  = "'.$grade_id.'"')->find();
        if(empty($grade)){
            output_error('该年级不存在或已被删除，请联系管理员');
        }
        $class = db('class')->field('classid as value,classname as title')->where('schoolid = "'.$school_id.'" AND typeid = "'.$grade_id.'"')->select();
        output_data($class);


    }

    /**
     * @desc 上传头像
     * @author langzhiyao
     * @time 20181012
     */
    public function upload_avatar(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        if(!empty($_FILES)){
            if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")))
            {
                if($_FILES["file"]["size"] < 80000){
                    if ($_FILES["file"]["error"] > 0)
                    {
                        output_error($_FILES["file"]["error"]);
                    }
                    else
                    {
                        if (!empty($_FILES['file']['tmp_name'])) {
                            $file_object= request()->file('file');
                            $base_url=BASE_UPLOAD_PATH . '/' . ATTACH_AVATAR . '/';
                            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                            $file_name='avatar_'.$member_id.'_'.time().rand(1000,9999).".$ext";
                            $info = $file_object->rule('uniqid')->validate(['ext' => 'jpg,png,gif,jpeg'])->move($base_url,$file_name);
                            if (!$info) {
                                output_error($base_url);
                                output_error($file_object->getError());
                            }
                        } else {
                            output_error('上传失败，请尝试更换图片格式或小图片');
                        }
                        $name_dir= '/' . ATTACH_AVATAR . '/' . $info->getFilename();
                        $imageinfo=getimagesize($name_dir);
                        $file_dir=UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.'/'.$info->getFilename();
//                        halt($file_dir);
                        $result[] = array('message'=>'修改成功','avatar_url'=>$name_dir);
                        output_data($result);
                    }
                }else{
                    output_error('图片上传大小不允许超过8M，请重新上传');
                }
            }
            else
            {
                output_error('图片上传类型不符合，请重新上传');
            }
        }

    }

}