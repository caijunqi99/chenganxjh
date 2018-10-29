<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Lookchild
{
    //看孩
    public function index(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            $data['name']='请登录';
            $data = !empty($data)?[$data]:$data;
            output_data($data);
        }else{
            $student=model('student');
            $result=$student->getAllChilds($member_id);
            if(empty($result)){
                $data['name']='请绑定学生';
                $data = !empty($data)?[$data]:$data;
                output_data($data);
            }else {
                foreach($result as $v){
                    $res[$v['s_id']]=$v['s_name'];
                }
                $data['name']=$res;
                $sid  = input('post.sid');
//                if(empty($sid)) {
//                    $data['student'] = $result[0];
//                }else{
//                    $data['student']=$student->getChildrenInfoByIdes($sid);
//                }
                $url='http://101.201.75.83:8050/?msgid=115&accountid=2025&authkey=webuser&deviceid=365';
                $html = json_decode(file_get_contents($url),true);
                foreach($html['device']['channels'] as $k=> $v){
                    $html['device']['channels'][$k]['status']=$html['device']['online'];
                }
                $data['camera']=$html['device']['channels'];
                $user['ip']="101.201.75.83";
                $user['port']='9001';
                $user['username']='test';
                $user['pwd']='123456';
                $data['logo']=$user;
                $data = !empty($data)?[$data]:$data;
                output_data($data);
                }
        }
    }


}