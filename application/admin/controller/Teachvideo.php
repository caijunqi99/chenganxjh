<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
vendor('qiniu.autoload');
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Teachvideo extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/teacher.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
//        $class_name = strtolower(end(explode('\\',__CLASS__)));
//        $perm_id = $this->get_permid($class_name);
//        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
//        $this->assign('action',$action);
    }

    public function index() {
//        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $model_teach = model('Teachchild');
        $condition = array();
//        $admininfo = $this->getAdminInfo();
//        if($admininfo['admin_id']!=1){
//            $admin = db('admin')->where(array('admin_id'=>$admininfo['admin_id']))->find();
//            $condition['a.admin_company_id'] = $admin['admin_company_id'];
//        }
        $user = input('param.user');//会员账户
        if ($user) {
            $condition['member_mobile'] = array('like', "%" . $user . "%");
        }
        $teacher_status = input('param.teacher_status');//状态
        if ($teacher_status) {
            $condition['t_audit'] = $teacher_status;
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['t_maketime'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['t_maketime'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['t_maketime'] = array('<',strtotime($query_end_time));
        }
        $type4 = input('param.type4');
        $type3 = input('param.type3');
        $type2 = input('param.type2');
        $type1 = input('param.type1');

        if($type4){
            $condition['t_type4'] = $type4;
            $teachtype4 = db('teachtype')->where(array('gc_parent_id'=>$type3))->select();
            $this->assign('teachtype4', $teachtype4);
            $teachtype3 = db('teachtype')->where(array('gc_parent_id'=>$type2))->select();
            $this->assign('teachtype3', $teachtype3);
            $teachtype2 = db('teachtype')->where(array('gc_parent_id'=>$type1))->select();
            $this->assign('teachtype2', $teachtype2);
        }elseif($type3){
            $condition['t_type3'] = $type3;
            $teachtype4 = db('teachtype')->where(array('gc_parent_id'=>$type3))->select();
            $this->assign('teachtype4', $teachtype4);
            $teachtype3 = db('teachtype')->where(array('gc_parent_id'=>$type2))->select();
            $this->assign('teachtype3', $teachtype3);
            $teachtype2 = db('teachtype')->where(array('gc_parent_id'=>$type1))->select();
            $this->assign('teachtype2', $teachtype2);
        }elseif($type2){
            $condition['t_type2'] = $type2;
            $teachtype2 = db('teachtype')->where(array('gc_parent_id'=>$type1))->select();
            $this->assign('teachtype2', $teachtype2);
            $teachtype3 = db('teachtype')->where(array('gc_parent_id'=>$type2))->select();
            $this->assign('teachtype3', $teachtype3);
        }elseif($type1){
            $condition['t_type'] = $type1;
            $teachtype2 = db('teachtype')->where(array('gc_parent_id'=>$type1))->select();
            $this->assign('teachtype2', $teachtype2);
        }
        $condition['t_del'] = 1;
        $teacher_list = $model_teach->getTeachchildList($condition, 15);
        foreach($teacher_list as $k=>$v){
            if($v['member_mobile']=="后台"){
                $teacher_list[$k]['username'] = "后台";
            }else{
                $relinfo = db('teachercertify')->where(array('member_id'=>$v['t_userid']))->find();
                $teacher_list[$k]['username'] = $relinfo['username'];
            }
            $teacher_list[$k]['type'] = $this->type($v);
        }
        $teachtype = db('teachtype')->where(array('gc_parent_id'=>0))->select();
        $this->assign('teachtype', $teachtype);
        $img_path = "http://".$_SERVER['HTTP_HOST']."/uploads/";
        $this->assign('img_path', $img_path);
        $this->assign('page', $model_teach->page_info->render());
        $this->assign('teach_list', $teacher_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function type($data){
        if(!empty($data['t_type'])){
            $last = db('teachtype')->where(array('gc_id'=>$data['t_type']))->find();
        }
        if(!empty($data['t_type2'])){
            $last2 = db('teachtype')->where(array('gc_id'=>$data['t_type2']))->find();
        }
        if(!empty($data['t_type3'])){
            $last3 = db('teachtype')->where(array('gc_id'=>$data['t_type3']))->find();
        }
        if(!empty($data['t_type4'])){
            $last4 = db('teachtype')->where(array('gc_id'=>$data['t_type4']))->find();
        }
        if($last4 && $last3 && $last2 && $last){
            $type = $last['gc_name'].'-'.$last2['gc_name'].'-'.$last3['gc_name'].'-'.$last4['gc_name'];
        }elseif($last3 && $last2 && $last){
            $type = $last['gc_name'].'-'.$last2['gc_name'].'-'.$last3['gc_name'];
        }elseif($last2 && $last){
            $type = $last['gc_name'].'-'.$last2['gc_name'];
        }elseif($last){
            $type = $last['gc_name'];
        }
        return $type;
    }

    public function fand_type(){
        $gc_id = intval(input('post.gc_id'));
        if($gc_id){
            $teachtype = db('teachtype')->where(array('gc_parent_id'=>$gc_id))->select();
            echo json_encode($teachtype);
        }
    }

    public function pass() {
//        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $teacher_id = input('param.t_id');
        if (empty($teacher_id)) {
            $this->error(lang('param_error'));
        }
        $model_teacher = model('Teachchild');
        if (!request()->isPost()) {
            $teachinfo = $model_teacher->getTeachchildInfo(array('t_id'=>$teacher_id));
            $teachinfo['type'] = $this->type($teachinfo);
            if($teachinfo['t_userid']){
                $teachercertify = db('teachercertify')->where(array('member_id'=>$teachinfo['t_userid']))->find();
                $teachinfo['name'] = $teachercertify['username'];
            }
            $path = "http://vip.xiangjianhai.com:8001/uploads/";
            $this->assign('path', $path);
            $this->assign('teachinfo', $teachinfo);
            $this->setAdminCurItem('pass');
            return $this->fetch();
        } else {
            $data = array(

                'name' => input('post.school_name'),
                'areaid' => input('post.area_id'),
                'region' => input('post.area_info'),
                'typeid' => implode(",",$_POST['school_type']),
                'address' => input('post.school_address'),
                'common_phone' => input('post.school_phone'),
                'username' => input('post.school_username'),
                'dieline' => input('post.school_dieline'),
                'desc' => input('post.school_desc'),
                'createtime' => date('Y-m-d H:i:s',time())

            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['provinceid'] = $province_id['area_parent_id'];
            //print_r($school_id);die;
            //验证数据  END
            $result = $model_school->editSchool($data,array('schoolid'=>$school_id));
            if ($result) {
                $this->success('编辑成功', 'School/member');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    public function edit() {
        $video_id = input('param.video_id');
        if (empty($video_id)) {
            $this->error(lang('param_error'));
        }
        $model_teacher = model('Teachchild');
        if (!request()->isPost()) {
            $condition['t_id'] = $video_id;
            $video_info = $model_teacher->getTeachchildInfo($condition);
            $this->assign('video_info', $video_info);
            //分类
            $teachtype = db('teachtype')->where(array('gc_parent_id'=>0))->select();
            $this->assign('teachtype', $teachtype);
            if($video_info['t_type2']!=""){
                $teachtype2 = db('teachtype')->where(array('gc_parent_id'=>$video_info['t_type']))->select();
                $this->assign('teachtype2', $teachtype2);
            }
            if($video_info['t_type3']!=""){
                $teachtype3 = db('teachtype')->where(array('gc_parent_id'=>$video_info['t_type2']))->select();
                $this->assign('teachtype3', $teachtype3);
            }
            if($video_info['t_type4']!=""){
                $teachtype4 = db('teachtype')->where(array('gc_parent_id'=>$video_info['t_type3']))->select();
                $this->assign('teachtype4', $teachtype4);
            }
            $img_path = "http://".$_SERVER['HTTP_HOST']."/uploads/";
            $this->assign('path', $img_path);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {
            $data = array(
                't_title' => input('post.video_name'),
                't_desc' => input('post.video_desc'),
                't_profile' => input('post.t_profile'),
                't_price' => input('post.video_price'),
                't_author' => input('post.video_author'),
                't_type' => input('post.type1'),
                't_type2' => input('post.type2'),
                't_type3' => input('post.type3'),
                't_type4' => input('post.type4'),
                't_audit' => 1
            );
            if($_FILES['video_filename']['name']){
                $data['t_picture'] = "home/videoimg/".date("YmdHis",time())."_".time().".png";
                $this->image($data['t_picture']);
            }
            //验证数据  END
            $model_teacher = model('Teachchild');
            $result = $model_teacher->editTeachchild(array('t_id'=>$video_id),$data);
            if ($result) {
                $this->success('编辑成功', 'Teachvideo/index');
            } else {
                $this->error('编辑失败');
            }
        }
    }


    /**
     * ajax操作
     */
    public function ajax() {
        $branch = input('param.branch');

        switch ($branch) {
            /**
             * 验证学校名是否重复
             */
            case 'check_user_name':
                $school_member = Model('school');
                $condition['name'] = input('param.school_name');
                $condition['areaid'] = array('eq', intval(input('get.area_id')));
                $condition['isdel'] = 1;
                $list = $school_member->getSchoolInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 验证班级名是否重复
             */
            case 'check_class_name':
                $class_member = Model('classes');
                $condition['classname'] = input('param.class_name');
                $condition['schoolid'] = input('param.school_id');
                $condition['isdel'] = 1;
                $list = $class_member->getClassInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**
     * 重要提示，删除会员 要先确定删除店铺,然后删除会员以及会员相关的数据表信息。这个后期需要完善。
     */
    public function drop() {
//        if(session('admin_is_super') !=1 && !in_array(2,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $admininfo = $this->getAdminInfo();

        $teacher_id = input('param.t_id');
        $status = input('param.t_audit');
        $phone = input('param.phone');
        $reason = input('param.reason')? input('param.reason') : "";
        if (empty($teacher_id)) {
            $this->error(lang('param_error'));
        }
        $model_teacher = model('Teachchild');
        $result = $model_teacher->editTeachchild(array('t_id'=>$teacher_id),array('t_audit'=>$status,'t_failreason'=>$reason,'option_id'=>$admininfo['admin_id'],'option_time'=>time()));
        if ($result) {
            if($status==2){
                //审核结果给用户发送短信提醒
                if(preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $phone)){
                    if(empty($reason)){
                        $content = '您的教孩视频审核未通过。请登录想见孩app重新上传!';
                    }else{
                        $content = '您的教孩视频审核未通过，失败原因是：'.$reason."。请登录想见孩app重新上传!";
                    }
                    $sms = new \sendmsg\sdk\SmsApi();
                    $send = $sms->sendSMS($phone,$content);
                    if(!$send){
                        $this->error('给用户发送短信失败 ');
                    }
                }
                $this->success(lang('teacher_index_noapass'), 'Teachvideo/index');
            }else{
                //审核结果给用户发送短信提醒
                if(preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $phone)){
                    $content = '尊敬的'.$phone.'，您的教孩视频已审核通过，感谢您的支持！ ';
                    $sms = new \sendmsg\sdk\SmsApi();
                    $send = $sms->sendSMS($phone,$content);
                    if(!$send){
                        $this->error('给用户发送短信失败 ');
                    }
                }
                $this->success(lang('teacher_index_apass'), 'Teachvideo/index');
            }
        } else {
            $this->error('审核失败');
        }
    }

    /*
     * 设置推荐视频
     * */
    public function recom() {
        if(session('admin_is_super') !=1 && !in_array(2,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $t_id = input('param.t_id');
        $recommend = input('param.t_recommend');
        if (empty($t_id)) {
            $this->error(lang('param_error'));
        }
        $model_teachchild = Model('Teachchild');
        $result = $model_teachchild->editTeachchild(array('t_id'=>$t_id),array('t_recommend'=>$recommend));
        if ($result) {
            $this->success("推荐设置成功", 'Teachvideo/index');
        } else {
            $this->error('推荐设置失败');
        }
    }

    /*
     * 上传视频
     * */
    public function add() {
        $model_class = model('Teachchild');
        if (!request()->isPost()) {
            $teachtype = db('teachtype')->where(array('gc_parent_id'=>0))->select();
            $this->assign('teachtype', $teachtype);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $insert_array = array();
            $insert_array['t_title'] = input('post.video_name');
            $insert_array['t_desc'] = input('post.video_desc');
            $insert_array['t_profile'] = input('post.t_profile');
            $insert_array['t_price'] = input('post.video_price');
            $insert_array['t_author'] = input('post.video_author');
            $insert_array['t_type'] = input('post.type1');
            $insert_array['t_type2'] = input('post.type2');
            $insert_array['t_type3'] = input('post.type3');
            $insert_array['t_type4'] = input('post.type4');
            $insert_array['t_audit'] = 1;
            $insert_array['member_mobile'] = "后台";
            $insert_array['t_maketime'] = time();
            //上传视频封面图
            if($_FILES['video_filename']['name']){
                $insert_array['t_picture'] = "home/videoimg/".date("YmdHis",time())."_".time().".png";
                $this->image($insert_array['t_picture']);
            }
            //上传视频
//            $videoData = $this->video($_FILES);
//            $insert_array['t_url'] = $videoData['path'];//视频路径
//            $insert_array['t_videoimg'] = $videoData['pic'];//默认封面图
//            $insert_array['t_timelength'] = $videoData['time'];//视频时长
            $insert_array['t_url'] = input('post.path')?input('post.path'):"";//视频路径
            $insert_array['t_videoimg'] = input('post.pic')?input('post.pic'):"";;//默认封面图
            $insert_array['t_timelength'] = input('post.time')?input('post.time'):"";//视频时长

            //验证数据  END
            if($insert_array['t_url']!="" && $insert_array['t_videoimg']!=""){
                $result = $model_class->addTeachchild($insert_array);
            }
            if ($result) {
                $this->success(lang('ds_common_save_succ'), url('Admin/Teachvideo/index'));
            } else {
                $this->error(lang('ds_common_save_fail'), url('Admin/Teachvideo/index'));
            }

        }
    }

    /*
     * 上传图片
     * */
    public function image($picture){
        //上传路径
        $uploadimg_path = substr(str_replace("\\","/",$_SERVER['SCRIPT_FILENAME']),'0','-9')."uploads/";
        //检查是否有该文件夹，如果没有就创建
        if(!is_dir($uploadimg_path."home/videoimg/")){
            mkdir($uploadimg_path."home/videoimg/",0777,true);
        }
        //允许上传的文件格式
        $tp = array("image/gif","image/jpeg","image/jpg","image/png","image/*");
        //检查上传文件是否在允许上传的类型
        if(!in_array($_FILES["video_filename"]["type"],$tp))
        {
            $this->error(lang("格式不正确"), url('Admin/Teachvideo/index'));
        }
        if (!empty($_FILES['video_filename']['name'])) {
            $upload = move_uploaded_file($_FILES["video_filename"]["tmp_name"], $uploadimg_path . $picture);
            if($upload){
                return $upload;
            }else{
                $this->error(lang("上传视频封面图失败"), url('Admin/Teachvideo/index'));
            }
        }
    }

    /*
     * 上传视频
     * 保存在七牛云上
     * */
    public function video($data){
        //获取文件的名字//
        //$key = $data['video_file']['name'];
        $key = "admin_video_".date("YmdHis",time())."_".time().".mp4";
        $filePath=$data['video_file']['tmp_name'];
        //获取token值
        $accessKey = 'V0Su976FmQMUBKKf9TLZIYao34G-l6RN_7zxhfFV';
        $secretKey = 'xvVkqpveV8myyeHYP4c_tghcPRUKUmvc2EqbOumG';
        $WAILIAN='pgj4a41j8.bkt.clouddn.com';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'avatar';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        // 获取视频的时长
        // 第一步先获取到到的是关于视频所有信息的json字符串
        $shichang = file_get_contents('http://'.$WAILIAN.'/'.$key.'?avinfo');
        // 第二部转化为对象
        $shi =json_decode($shichang);
        // 第三部从中取出视频的时长
        $chang = $shi->format->duration;
        // 获取封面
        $vpic = 'http://'.$WAILIAN.'/'.$key.'?vframe/jpg/offset/1';
        $path ='http://'.$WAILIAN.'/'.$ret['key'];
        $data = [
            'path' => $path,
            'pic' =>$vpic,
            'time'=>$chang,
        ];
        return $data;
    }

    public function video_data(){
        $fileName = $_FILES["file1"]["name"]; // The file name
        $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
        $fileType = $_FILES["file1"]["type"]; // The type of file it is
        $fileSize = $_FILES["file1"]["size"]; // File size in bytes
        $fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
        if (!$fileTmpLoc) { // if file not chosen
            echo "ERROR: Please browse for a file before clicking the upload button.";
            exit();
        }
        //获取文件的名字//
        $key = "admin_video_".date("YmdHis",time())."_".time().".mp4";
        $filePath=$fileTmpLoc;
        //获取token值
        $accessKey = 'V0Su976FmQMUBKKf9TLZIYao34G-l6RN_7zxhfFV';
        $secretKey = 'xvVkqpveV8myyeHYP4c_tghcPRUKUmvc2EqbOumG';
        $WAILIAN='pgj4a41j8.bkt.clouddn.com';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'avatar';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        // 获取视频的时长
        // 第一步先获取到到的是关于视频所有信息的json字符串
        $shichang = file_get_contents('http://'.$WAILIAN.'/'.$key.'?avinfo');
        // 第二部转化为对象
        $shi =json_decode($shichang);
        // 第三部从中取出视频的时长
        $chang = $shi->format->duration;
        // 获取封面
        $vpic = 'http://'.$WAILIAN.'/'.$key.'?vframe/jpg/offset/1';
        $path ='http://'.$WAILIAN.'/'.$ret['key'];
        $data = [
            'path' => $path,
            'pic' =>$vpic,
            'time'=>$chang,
        ];
        echo json_encode($data);
        exit;
    }

    //删除
    public function del() {
        $video_id = input('param.video_id');
        if (empty($video_id)) {
            $this->error(lang('param_error'));
        }
        $model_video = Model('Teachchild');
        $result = $model_video->editTeachchild(array('t_id'=>$video_id),array('t_del'=>2));
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'Teachvideo/index');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '管理',
                'url' => url('Admin/Teachvideo/index')
            ),
        );
        if (request()->action() == 'add' || request()->action() == 'index') {
            $menu_array[] = array(
                'name' => 'add',
                'text' => '上传视频',
                'url' => url('Admin/Teachvideo/add')
            );
        }
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Teachvideo/edit')
            );
        }
        if (request()->action() == 'pass') {
            $menu_array[] = array(
                'name' => 'pass',
                'text' => '审核',
                'url' => url('Admin/Teachvideo/pass')
            );
        }
        return $menu_array;
    }

}

?>
