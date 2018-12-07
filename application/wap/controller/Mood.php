<?php

namespace app\wap\controller;



class Mood extends MobileMember{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        header("Access-Control-Allow-Origin: *");
    }
    /**
     * app用户自己的心情列表
     */
    public function mood() {
        $member_id  = intval(input('post.member_id'));
        if (empty($member_id)) {
            output_error('参数有误');
        }

        $model_mood = Model('mood');
        $condition=array();
        $condition['member_id']=$member_id;
        $condition['del']=1;
        $start = 0;
        $page_num = 5;
        if(input('post.start')){
            $start =$page_num*intval(input('post.start'));
        }
        $data = $model_mood->where($condition)->limit($start,$page_num)->order('id DESC')->select();

        $mood_count = db('mood')->where($condition)->count();
        $page_count = ceil(intval($mood_count)/intval($page_num));
        $ar = array('page_count'=>$page_count);

        $memberinfo = db('member')->where(array('member_id'=>$member_id))->find();
        if($memberinfo['member_nickname']==''){
            $xing = substr($memberinfo['member_mobile'],3,4);
            $logindata['member_nickname']=str_replace($xing,'****',$memberinfo['member_mobile']);
        }else {
            $logindata['member_nickname'] = $memberinfo['member_nickname'];
        }
        $logindata['member_avatar'] = $memberinfo['member_avatar'];

        foreach ($data as $k=>$v){
            $data[$k]['day'] = date("d",$v['pubtime']);
            $data[$k]['month'] = date("m",$v['pubtime']);
            $data[$k]['pubtime'] = date("Y-m-d",$v['pubtime']);
            $data[$k]['ago'] = $model_mood->getnum($v['pubtime']);
            $data[$k]['image']=explode(',',$v['smallimage']);
        }

        $logindata['mood'] = $data;
        $logindata['page_count'] =$ar;

        if($logindata){
            output_data($logindata);
        }else{
            output_data(array());
        }

    }
    /**
     *所有用户心情列表
     */
    public function  moodlist(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('会员id不能为空');
        }
        $where = array();
        $where['m.del']=1;
        $start = 0;
        $page_num = 5;
        if(input('post.start')){
            $start =$page_num*intval(input('post.start'));
        }
        //查询被该会员拉黑的会员
        $lh_member = db('moodlh')->where('member_id="'.$member_id.'"')->select();
        $str = '';
        if(!empty($lh_member)){
            var_dump($lh_member);
            foreach ($lh_member as $key=>$value){
                $str .= $value['lh_member_id'].',';
            }
        }
        if(!empty($str)){
            $string = explode(',',trim($str,','));
            var_dump($string);
            $where['m.member_id']=array('not in ',$string);
        }

        $mood_list = db('mood')->alias('m')
            ->field('m.*,b.member_nickname,b.member_avatar,b.member_name,b.member_mobile')
            ->join('__MEMBER__ b', 'b.member_id = m.member_id', 'LEFT')
            ->where($where)
            ->limit($start,$page_num)
            ->order('id desc')
            ->select();
        $mood_count = db('mood')->alias('m')->where($where)->count();
        $page_count = ceil(intval($mood_count)/intval($page_num));
        $ar = array('page_count'=>$page_count);
        foreach($mood_list as $k=>$v){
            $mood_list[$k]['image']=explode(',',$v['smallimage']);
            $mood_list[$k]['pubtime'] = date("Y-m-d H:i",$v['pubtime']);
            if($v['member_nickname']==''){
                $xing = substr($v['member_mobile'],3,4);
                $mood_list[$k]['member_nickname']=str_replace($xing,'****',$v['member_mobile']);
            }
            if(!empty($mood_list[$k]['member_avatar'])){
                $mood_list[$k]['rel_member_avatar'] = UPLOAD_SITE_URL.$mood_list[$k]['member_avatar'];
            }else{
                $mood_list[$k]['member_avatar'] = '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
                $mood_list[$k]['rel_member_avatar'] = UPLOAD_SITE_URL . '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
            }
        }

        $data[0] = $mood_list;
        $data[1] = $ar;
        output_data($data);
    }
    /**
     * 添加心情
    */
    public function moodadd(){
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('会员id不能为空');
        }
        $mood = model('Mood');
        $condition = array();
        $condition['member_id']=$member_id;
        $condition['content']=trim(input('post.content'));
        $condition['pubtime']=time();
        $condition['click']=0;
        $condition['del']=1;
        if(!empty($_FILES['file']['name'][0])) {
            foreach ($_FILES['file']['name'] as $key => $value) {
                $file1 = array();
                $imgname=($key+1).date("YmdHis",time())."_".time().".".end(explode('.', $value));
                $file1["file"]['name'] = "home/moodimg/".$imgname;
                $file1["file"]['type'] = $_FILES['file']["type"][$key];
                $file1["file"]['tmp_name'] = $_FILES['file']["tmp_name"][$key];
                $file1["file"]['error'] = $_FILES['file']["error"][$key];
                $file1["file"]['size'] = $_FILES['file']["size"][$key];
                $smallname="home/smallmood/".$imgname;
                $file1["file"]['small']=$smallname;

                $info = $this->upload($file1);
                if($info){
                    $a .="," . $file1["file"]['name'];
                    $b .=",".$smallname;
                }else{
                    output_error('图片上传失败');
                }
            }
            //把第一个#去掉，同时写进data数据库里面的intro_pic字段
            $condition['image']= substr($a, 1);
            $condition['smallimage']=substr($b, 1);
        }
        $result=$mood->addMood($condition);
        if($result){
            output_data(array('message'=>'发布成功'));
        }else{
            output_error('发布失败');
        }
    }
    
    public function uploadThump(){
        if(!empty($_FILES)){
            if($_FILES["mood"]["size"] < 8*1024*1024){
                if ($_FILES["mood"]["error"] > 0){
                    output_error($_FILES["mood"]["error"]);
                }else{
                    //根目录地址
                    $base_url = str_replace('\\', '/',BASE_UPLOAD_PATH );
                    //正常上传的地址
                    $big_url=$base_url .'/'. ATTACH_PATH . '/mood/';
                    //压缩后的图片上传的地址
                    $small_url=$big_url.'smallmood/';

                    if (!empty($_FILES['mood']['tmp_name'])) {
                        $file_object= request()->file('mood');
                        $ext = strtolower(pathinfo($_FILES['mood']['name'], PATHINFO_EXTENSION));
                        $file_name='mood_'.time().rand(1000,9999).".$ext";
                        $info = $file_object->rule('uniqid')->validate(['ext' => 'jpg,png,gif,jpeg'])->move($big_url,$file_name);

                        if (!$info) output_error($file_object->getError());
                    } else {
                        output_error('上传失败，请尝试更换图片格式或小图片');
                    }
                    $imgUlr =$info->getFilename();
                    $big_name_dir= '/' . ATTACH_PATH . '/mood/' . $imgUlr;
                    $imageinfo=getimagesize(BASE_UPLOAD_PATH.$big_name_dir);
                    $file_dir=$big_url.$imgUlr;

                    $image = \think\Image::open($file_dir);
                    // 按照原图的比例生成一个最大为600*600的缩略图替换原图
                    // p($base_url.'smallmood/' . $inmUrl);exit;
                    $image->thumb(110, 110)->save($small_url. $imgUlr);
                    $small_name_dir ='/' . ATTACH_PATH . '/mood/smallmood/' . $imgUlr;
                    
                    $result = array();
                    $result[0] = array(
                        'big_name_dir' => $big_name_dir,//原图相对路径
                        'big_file_dir' => $big_url. $imgUlr,//原图绝对路径
                        'big_imageinfo' => getimagesize($big_url. $imgUlr),//原图详情
                        'small_name_dir' => $small_name_dir, //压缩后的图片相对路径
                        'small_file_dir' => $small_url. $imgUlr,//压缩后的图片绝对路径
                        'small_imageinfo' => getimagesize($small_url. $imgUlr), //压缩图详情 
                        'error' =>$file_object->getError() //上传错误，正常为空
                    );
                    output_data($result);
                }
            }else{
                output_error('图片上传大小不允许超过8M，请重新上传');
            }
        }
    }
    /*
 * 上传图片
 * */
    public function upload($data){
        //上传路径
        $uploadimg_path = substr(str_replace("\\","/",$_SERVER['SCRIPT_FILENAME']),'0','-9')."uploads/";
        //检查是否有该文件夹，如果没有就创建
        if(!is_dir($uploadimg_path."home/moodimg/")){
            mkdir($uploadimg_path."home/moodimg/",0777,true);
        }
        //允许上传的文件格式
        $tp = array("image/gif","image/jpeg","image/jpg","image/png","image/*");
        //检查上传文件是否在允许上传的类型
        if(!in_array($data["file"]["type"],$tp))
        {
            return '图片上传类型不符合，请重新上传';
        }
        if($data["file"]["size"] < 8*1024*1024) {
            if (!empty($data['file']['name'])) {
                $upload = move_uploaded_file($data["file"]["tmp_name"], $uploadimg_path . $data['file']['name']);
                $image = \think\Image::open($uploadimg_path.$data['file']['name']);
                //检查是否有该文件夹，如果没有就创建
                if(!is_dir($uploadimg_path."home/smallmood/")){
                    mkdir($uploadimg_path."home/smallmood/",0777,true);
                }
                // 按照原图的比例生成一个最大为600*600的缩略图替换原图
                 //p($uploadimg_path. $data['file']['small']);exit;
                $image->thumb(160, 210)->save($uploadimg_path. $data['file']['small']);
                if ($upload) {
                    return $upload;
                } else {
                    return '上传图片失败';
                }
            }
        }else{
            return '图片上传大小不允许超过8M，请重新上传';
        }
    }
    /**
     * 心情删除
     */
    public function mooddel()
    {
        $id = intval(input('post.id'));
        $member_id = intval(input('post.member_id'));
        if (empty($member_id)) {
            output_error('会员id不能为空');
        }
        $mood_admin = Model('mood');
        $res = $mood_admin->getOneById($id);
        if (!empty($res)) {
            if ($res['member_id'] == $member_id) {
                if ($res['del'] == 1) {
                    $where = array();
                    $where['id']=$id;
                    $update_array = array();
                    $update_array['del'] = 2;
                    $update_array['deltime'] = time();
                    $list = $mood_admin->editMood($where, $update_array);
                    if (!empty($list)) {
                        output_data(array('message' => '删除成功'));
                    } else {
                        output_error('删除失败');
                    }
                } else {
                    output_error('已经被删除了');
                }
            } else {
                output_error('不能删除他人动态');
            }
        } else {
            output_error('无此心情，该心情已被删除');
        }
    }
    /**
     * 心情屏蔽
     */
    public function mood_lh()
    {
        $id = intval(input('post.id'));
        $member_id = intval(input('post.member_id'));
        if (empty($member_id)) {
            output_error('会员id不能为空');
        }
        $mood_admin = Model('mood');
        $res = $mood_admin->getOneById($id);
        if (!empty($res)) {
            if ($res['member_id'] != $member_id) {
                //查看该发布动态会员是否已经被拉黑
                $is_lh = db('moodlh')->where('member_id="'.$member_id.'" AND lh_member_id="'.$res["member_id"].'"')->find();
                if (!$is_lh) {
                    $data = array(
                        'member_id'=>$member_id,
                        'lh_member_id'=>$res['member_id'],
                        'time' => time()
                    );
                    $result = db('moodlh')->insert($data);
                    if (!empty($result)) {
                        output_data(array('message' => '已屏蔽此用户'));
                    } else {
                        output_error('屏蔽此用户失败');
                    }
                } else {
                    output_error('该用户已被您屏蔽，无法重复操作');
                }
            } else {
                output_error('不能屏蔽自己的动态');
            }
        } else {
            output_error('无此心情，该心情已被删除');
        }
    }
    /**
     * 心情回复
    */
    public function moodview(){
        $memberid = intval(input('post.memberid'));
        $mid = intval(input('post.mid'));
        $content=trim(input('post.content'));
        if (empty($memberid)) {
            output_error('会员id不能为空');
        }
        if (empty($mid)) {
            output_error('心情id不能为空');
        }
        if (empty($content)) {
            output_error('回复内容不能为空');
        }
        $moodview = Model('moodview');
        $arr=array();
        $arr['v_mid']=$mid;
        $arr['v_content']=$content;
        $arr['v_memberid']=$memberid;
        $arr['v_pubtime']=time();
        $res=$moodview->addMood($arr);
        if($res){
            $model_mood = Model('mood');
            $result=$model_mood->getOneById($mid);
            $where['id']=$mid;
            $update['click']=$result['click']+1;
            $model_mood->editMood($where,$update);
            output_data(array('message' => '回复成功'));
        }else{
            output_error('回复失败');
        }
    }
    /**
     * 心情详情页
    */
    public function mooddetail(){
        $where = array();
        $where['id']=intval(input('post.id'));
        $mood = db('mood')->alias('m')->field('m.*,b.member_nickname,b.member_avatar,b.member_name,b.member_mobile')->join('__MEMBER__ b', 'b.member_id = m.member_id', 'LEFT')->where($where)->find();
        $mood['image']=explode(',',$mood['image']);
        if($mood['member_nickname']==''){
            $xing = substr($mood['member_mobile'],3,4);
            $mood['member_nickname']=str_replace($xing,'****',$mood['member_mobile']);
        }
        if($mood['member_avatar'] != ''){
            $mood['rel_member_avatar'] = UPLOAD_SITE_URL.$mood['member_avatar'];
        }else{
            $mood['member_avatar'] = '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
            $mood['rel_member_avatar'] = UPLOAD_SITE_URL . '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
        }
        $mood['pubtime']=date("Y-m-d H:i:s",$mood['pubtime']);
        $contient=array();
        $contient['v_mid']=$mood['id'];
        $moodview=db('moodview')->alias('m')->field('m.*,b.member_nickname,b.member_avatar,b.member_name,b.member_mobile')->join('__MEMBER__ b', 'b.member_id = m.v_memberid', 'LEFT')->where($contient)->select();
        foreach($moodview as $key=>$v){
            if($v['member_nickname']==''){
                $xing = substr($v['member_mobile'],3,4);
                $moodview[$key]['member_nickname']=str_replace($xing,'****',$v['member_mobile']);
            }
            $moodview[$key]['v_pubtime']=date("Y-m-d H:i:s",$v['v_pubtime']);
            if(!empty($v['member_avatar'])){
                $moodview[$key]['rel_member_avatar'] = UPLOAD_SITE_URL.$v['member_avatar'];
            }else{
                $moodview[$key]['member_avatar'] = '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
                $moodview[$key]['rel_member_avatar'] = UPLOAD_SITE_URL . '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
            }
        }
        $list['mood']=$mood;
        $list['view']=$moodview;
        output_data($list);
    }
}