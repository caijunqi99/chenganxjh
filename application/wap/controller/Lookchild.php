<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;
use vomont\Vomont;

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
                if(empty($sid)) {
                    $schoolid = $result[0]['res_group_id'];
                    $classid=$result[0]['clres_group_id'];
                }else{
                    $str=$student->getChildrenInfoByIdes($sid);
                    $schoolid=$str['res_group_id'];
                    $classid=$str['clres_group_id'];
                }
                //$urls='http://117.78.26.155:8050/?msgid=110&authkey=webuser&username=test&pswd=e10adc3949ba59ab';
                $urls='http://101.201.75.83:8050/?msgid=110&authkey=webuser&username=bjc&pswd=e10adc3949ba59ab';
                $res = json_decode(file_get_contents($urls),true);
                $accountid=$res['accountid'];
                $user['ip']=$res['vlinkerip'];
                $user['port']=$res['vlinkerport'];
                $user['username']=$res['username'];
                $user['pwd']='123456';
                //$url='http://117.78.26.155:8050/?msgid=1280&accountid='.$accountid.'&authkey=webuser&restype=1&parentid='.$schoolid;
                $url='http://117.78.26.155:8050/?msgid=1280&accountid='.$accountid.'&authkey=webuser&restype=1&parentid=85';
                $html = json_decode(file_get_contents($url),true);
//                $urlcl='http://117.78.26.155:8050/?msgid=1280&accountid='.$accountid.'&authkey=webuser&restype=1&parentid='.$classid;
//                $htmlcl = json_decode(file_get_contents($urlcl),true);
//                foreach($htmlcl['resources'] as $v){
//                    $html['resources'][]=$v;
//                }
                foreach($html['resources'] as $k=> $v){
                    $html['resources'][$k]['status']=$v['online'];
                }
                $data['camera']=$html['resources'];
                $data['logo']=$user;
                $data = !empty($data)?[$data]:$data;
                output_data($data);
                }
        }
    }


}