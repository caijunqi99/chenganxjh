<?php

namespace app\office\controller;


class Common extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize('Common'); // TODO: Change the autogenerated stub
    }

    public function pic_upload()
    {
        if (input('param.form_submit')=='ok') {
            if (!empty($_FILES['_pic']['tmp_name'])) {
                $file_object = request()->file('_pic');
                $base_url = input('param.uploadpath');
                $image_url = BASE_UPLOAD_PATH . DS . $base_url;
                $info = $file_object->validate(['ext' => 'jpg,png,gif'])->move($image_url);
                if ($info) {
                    json_encode(array('status' => 1, 'url' => UPLOAD_SITE_URL . '/' . $base_url . '/' . $info->getFilename()));
                    exit;
                }
                else {
                    json_encode(array('status' => 0, 'msg' => $file_object->getError()));
                    exit;
                }
            }
        }
    }

    /**
     * 图片裁剪
     *
     */
    public function pic_cut()
    {
        if (request()->isPost()) {
            $thumb_width = $_POST['x'];
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $x2 = $_POST["x2"];
            $y2 = $_POST["y2"];
            $w = $_POST["w"];
            $h = $_POST["h"];
            $scale = $thumb_width / $w;
            $src = str_ireplace(config('url_domain_root'), ROOT_PATH, $_POST['url']);
            $src = str_replace('..', '', $src);

            $cropped = resize_thumb($src, $src, $w, $h, $x1, $y1, $scale);
            if (is_file($src)) {
                @unlink($src);
            }
            $pathinfo = pathinfo($src);
            exit($pathinfo['basename']);
        }
        $save_file = str_ireplace(config('url_domain_root'), ROOT_PATH, $_POST['url']);
        $_GET['x'] = (intval(input('param.x')) > 50 && input('param.x') < 400) ? input('param.x') : 200;
        $_GET['y'] = (intval(input('param.y')) > 50 && input('param.y') < 400) ? input('param.y') : 200;
        $_GET['resize'] = input('param.resize') == '0' ? '0' : '1';
        $imageinfo=getimagesize($save_file);
        $this->assign('height', $imageinfo[1]);
        $this->assign('width',  $imageinfo[0]);

        return $this->fetch($this->template_dir.'cut');

    }

    /**
     * 查询每月的周数组
     */
    public function getweekofmonthOp(){
        import('function.datehelper');
        $year = $_GET['y'];
        $month = $_GET['m'];
        $week_arr = getMonthWeekArr($year, $month);
        echo json_encode($week_arr);
        die;
    }
    /**
     * AJAX查询品牌
     */
    public function ajax_get_brandOp() {
        $initial = trim($_GET['letter']);
        $keyword = trim($_GET['keyword']);
        $type = trim($_GET['type']);
        if (!in_array($type, array('letter', 'keyword')) || ($type == 'letter' && empty($initial)) || ($type == 'keyword' && empty($keyword))) {
            echo json_encode(array());die();
        }

        // 实例化模型
        $model_type = Model('type');
        $where = array();
        // 验证类型是否关联品牌
        if ($type == 'letter') {
            switch ($initial) {
                case 'all':
                    break;
                case '0-9':
                    $where['brand_initial'] = array('in', array(0,1,2,3,4,5,6,7,8,9));
                    break;
                default:
                    $where['brand_initial'] = $initial;
                    break;
            }
        } else {
            $where['brand_name|brand_initial'] = array('like', '%' . $keyword . '%');
        }
        $brand_array = Model('brand')->getBrandPassedList($where, 'brand_id,brand_name,brand_initial', 0, 'brand_initial asc, brand_sort asc');
        echo json_encode($brand_array);die();
    }

    /**
     * @desc  excel表导入
     * @author langzhiyao
     * @time 20180927
     */
    public function file_upload()
    {
        session_start();//开启session

        unset($_SESSION['excel']);//清空session

        $schoolid = intval(input('post.school'));
        $file = request()->file('file'); // 获取上传的文件
        if($file==null){
            exit(json_encode(array('code'=>1,'msg'=>'未上传文件')));
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if(!in_array($extension,array("xlsx"))){
            exit(json_encode(array('code'=>1,'msg'=>'上传文件类型不合法')));
        }
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        // 移动文件到指定目录 没有则创建
        $file = '/uploads/'.$info->getSaveName();
        //excel表读取
        $file_name = ROOT_PATH.'public'.DS.'uploads/'.$info->getSaveName();   //上传文件的地址

        $excel = $this->goods_import($file_name,$extension);
        $_SESSION['excel']['excel_number'] = count($excel)-1;
//        halt($excel);
        //学校信息
        $school_info = db('school')->field('schoolid,name,provinceid,cityid,areaid,option_id,isdel')->where('schoolid = '.$schoolid.' AND isdel=1')->find();
        if(empty($school_info)){
            exit(json_encode(array('code'=>1,'msg'=>'该学校已被删除，请重新选择')));
        }
        $school_info['province'] = $this->get_address_name($school_info['provinceid']);
        $school_info['city'] = $this->get_address_name($school_info['cityid']);
        $school_info['area'] = $this->get_address_name($school_info['areaid']);
        $address = $school_info['province'].'-'.$school_info['city'].'-'.$school_info['area'];
        $school_info['address'] = $address;
        $_SESSION['excel']['school'] = $school_info;
//        halt($_SESSION['excel']['school']);
        //根据添加人获取添加人所属公司
        $agent_info = $this->get_agent($school_info['option_id']);
        $_SESSION['excel']['agent'] = $agent_info;

        $res = array();
        if(!empty($excel)){
//            halt($excel[1]['A']);
            if($excel[1]['A'] == '摄像头名称（最好能体现出具体的地方）' && $excel[1]['B'] == '绑定班级/区域' && $excel[1]['C'] == '是否公共区域' && $excel[1]['D'] == '所属学校名称' && $excel[1]['E'] == '所在地区' && $excel[1]['F'] == 'sn' && $excel[1]['G'] == 'key' && $excel[1]['H'] == '代理商/分公司' && $excel[1]['I'] == '备注' ){
                foreach($excel as $k=>$v){
                    if($k >1 && count($v) == 9 && $v['D'] == $school_info['name'] && !empty($v['F']) && !empty($v['G'])){
                        $result = db('camera')->where("`key`='".$v["G"]."'")->find();
                        if($result){
                            unset($v);
                        }else{
                            $res[]= $v;
                        }
                    }else{
                        unset($v);
                    }
                }
            }else{
                //表头不对
                exit(json_encode(array('code'=>1,'msg'=>'上传文件不符合规范，请按模板上传')));
            }
        }

        $_SESSION['excel']['excel_true_number'] = count($res);
//        halt($res);

        //将符合表的数据存session
        $_SESSION['excel']['excel_data'] = $res;


//        halt($_SESSION);

        exit(json_encode(array('code'=>0,'msg'=>$file)));

    }


    /**
     * @desc  学生excel表导入
     * @author langzhiyao
     * @time 20180927
     */
    public function PositionRecord()
    {
        $schoolid = intval(input('post.school'));
        $file = request()->file('file'); // 获取上传的文件
        if($file==null){
            exit(json_encode(array('code'=>1,'msg'=>'未上传文件')));
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if(!in_array($extension,array("xlsx",'xls'))){
            exit(json_encode(array('code'=>1,'msg'=>'上传文件类型不合法')));
        }
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');

        // 移动文件到指定目录 没有则创建
        $file = '/uploads/'.$info->getSaveName();

        //excel表读取
        $file_name = ROOT_PATH.'public'.DS.'uploads/'.$info->getSaveName();   //上传文件的地址

        $excel = GetExcelContent($file_name,$extension);
        
        //学校信息
        $schoolInfo = db('school')->field('schoolid,schoolCard,name,provinceid,cityid,areaid,region,option_id,typeid,isdel,admin_company_id')->where('schoolid = '.$schoolid.' AND isdel=1')->find();
        if(empty($schoolInfo)){
            exit(json_encode(array('code'=>1,'msg'=>'该学校已被删除，请重新选择')));
        }
        unset($excel[2]);
        sort($excel);
        $model_classes = model('Classes');
        $position = [];
        $data = [];        
        foreach ($excel as $k => $v) {
            //写入到教室位置表
            $position[$k] = array(
                'school_id' => $schoolid,
                'position' => $v['A'],
                'camera_num' => 0,

            );
            //写入到班级表
            $classcard=$schoolInfo['schoolCard'].($model_classes->getNumber($schoolInfo['schoolCard']));
            $qr = $this->MakeQr($classcard,$schoolInfo['schoolCard']);
            $class = array(
                'classname'         => $v['B'], // 班级名称
                'classCard'         => $classcard, //班级标识号
                'schoolid'          => $schoolid, //学校id
                'school_provinceid' => $schoolInfo['provinceid'], //省
                'school_cityid'     => $schoolInfo['cityid'], //市
                'school_areaid'     => $schoolInfo['areaid'], //县
                'school_region'     => $schoolInfo['region'], //地址
                'typeid'            => $schoolInfo['typeid'], // 默认全是幼儿园的
                'desc'              => $schoolInfo['name'], // 班级备注
                'qr'                => $qr, //二维码地址
                'isdel'             => 1, //1未删除
                'createtime'        => Fomat(time()), //创建时间
                'updatetime'        => Fomat(time()), ///修改时间
                'option_id'         => 1, 
                'admin_company_id'  => $schoolInfo['admin_company_id'],
                'res_group_id'      => 0,
                'is_true'           => 1,  //真实
            );
            $data[$k] = $model_classes->addClasses($class);
        }
        
        $position = model('Position')->positions_add($position);
        $class = $model_classes->class_add($class);


        exit(json_encode(array('code'=>0,'msg'=>$file)));

    }

    public function MakeQr($classcard,$schoolCard){
        //生成二维码
        import('qrcode.index',EXTEND_PATH);
        $PhpQRCode = new \PhpQRCode();
        $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_STORE . DS . 'class' . DS.$schoolCard.DS);
        // 生成班级二维码
        $PhpQRCode->set('date', $classcard);
        $PhpQRCode->set('pngTempName', $classcard . '.png');
        $qr=$PhpQRCode->init();
        $qr='/home/store/class/'.$schoolCard.'/'.$qr;
        return $qr;
    }

    function goods_import($filename, $exts = 'xls', $star =0, $encode = 'UTF-8') {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
//        import("Org.Util.PHPExcel");
        vendor('PHPExcel.PHPExcel');
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
//            import("Org.Util.PHPExcel.Reader.Excel5");
            vendor("PHPExcel.PHPExcel.Reader.Excel5");
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else if ($exts == 'xlsx') {
//            import("Org.Util.PHPExcel.Reader.Excel2007");
            vendor("PHPExcel.PHPExcel.Reader.Excel2007");
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        }
        //载入文件
        $PHPExcel = $PHPReader->load($filename, $encode);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet($star);
        //获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        //获取总行数
        $allRow = $currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            //从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                //数据坐标
                $address = $currentColumn . $currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
//        $this->save_import($data);
        return $data;
    }


    /**
     * @desc 根据省市区联动及学校信息
     * @author langzhiyao
     * @time 20180927
     */
    public function get_address_school(){
        $province_id = intval(input('get.province'));
        $city_id = intval(input('get.city'));
        $area_id = intval(input('get.area'));
        $agent_id = intval(input('get.agent'));
        $school_id = intval(input('get.school'));

        $city_html = '<option value="0">请选择市</option>';
        $area_html = '<option value="0">请选择县/区</option>';
        $agent_html = '<option value="0">请选择公司</option>';
        $school_html = '<option value="0">请选择学校</option>';
        $grade_html = '<option value="0">请选择学校类型</option>';
        $class_html = '<option value="0">请选择班级</option>';
        //学校
        $school_where['isdel'] = 1;
        //代理商
        $agent_where['o_del'] = 1;
        if(!empty($agent_id)){
            //学校
            $school_where['admin_company_id'] = $agent_id;
        }
        if(!empty($province_id)){
            //学校
            $school_where['provinceid'] = $province_id;
            //代理商
            $agent_where['o_provinceid'] = $province_id;
            if(!empty($city_id)){
                $school_where['cityid'] = $city_id;
                $agent_where['o_cityid'] = $city_id;
                if(!empty($area_id)){
                    $school_where['areaid'] = $area_id;
                    $agent_where['o_areaid'] = $area_id;
                }
                //县区
                $area_where['area_parent_id'] = $city_id;
                $area = db('area')->field('area_id,area_parent_id,area_name')->where($area_where)->select();
                if(!empty($area)){
                    foreach($area as $key=>$value){
                        if($value['area_id'] == $area_id){
                            $area_html .='<option value='.$value["area_id"].' selected>'.$value["area_name"].'</option>';
                        }else{
                            $area_html .='<option value='.$value["area_id"].'>'.$value["area_name"].'</option>';
                        }
                    }
                }
            }

            //市区
            $city_where['area_parent_id'] = $province_id;
            $city = db('area')->field('area_id,area_parent_id,area_name')->where($city_where)->select();
            if(!empty($city)){
                foreach($city as $key=>$value){
                    if($value['area_id'] == $city_id){
                        $city_html .='<option value='.$value["area_id"].' selected>'.$value["area_name"].'</option>';
                    }else{
                        $city_html .='<option value='.$value["area_id"].'>'.$value["area_name"].'</option>';
                    }
                }
            }
            //学校
            $school = db('school')->field('schoolid,name')->where($school_where)->select();
            if(!empty($school)){
                foreach($school as $key=>$value){
                    if($value['schoolid'] == $school_id){
                        $school_html .='<option value='.$value["schoolid"].' selected >'.$value["name"].'</option>';
                    }else{
                        $school_html .='<option value='.$value["schoolid"].' >'.$value["name"].'</option>';
                    }
                }
            }
            //代理商
            $agent = db('company')->field('o_id,o_name')->where($agent_where)->select();
            if(!empty($agent)){
                foreach($agent as $key=>$value){
                    if($value['o_id'] == $agent_id){
                        $agent_html .='<option value='.$value["o_id"].' selected>'.$value["o_name"].'</option>';
                    }else{
                        $agent_html .='<option value='.$value["o_id"].' >'.$value["o_name"].'</option>';
                    }
                }
            }

        }else{
            //学校
            $school = db('school')->field('schoolid,name')->where('isdel=1')->select();
            if(!empty($school)){
                foreach($school as $key=>$value){
                    $school_html .='<option value='.$value["schoolid"].'>'.$value["name"].'</option>';
                }
            }
            //代理商
            $agent = db('company')->field('o_id,o_name')->where('o_del=1')->select();
            if(!empty($agent)){
                foreach($agent as $key=>$value){
                    $agent_html .='<option value='.$value["o_id"].'>'.$value["o_name"].'</option>';
                }
            }
        }

        exit(json_encode(array('city'=>$city_html,'area'=>$area_html,'agent'=>$agent_html,'school'=>$school_html,'grade'=>$grade_html,'class'=>$class_html)));
    }

    /**
     * @desc 根据学校id获取年级和班级
     * @author langzhiyao
     * @time 20180927
     */
    public function get_school_info(){
        $school_id = intval(input('get.school'));
        $grade_name = trim(input('get.grade'));//年级类型ID
        $class_name = trim(input('get.class'));//年级类型ID


        $grade_html = '<option value="0">请选择学校类型</option>';
        $class_html = '<option value="0">请选择班级</option>';
        if(!empty($school_id)){
            /*if(!empty($grade_id)){

            }*/
            //班级
            $grade_where['schoolid'] = $school_id;
            $grade_class = db('schooltype')->field('sc_id,sc_type')->where(' `sc_type` = "'.$grade_name.'"')->find();
            $grade_where['typeid'] = $grade_class['sc_id'];
            $class =  db('class')->field('classid,classname')->where($grade_where)->select();
            if(!empty($class)){
                foreach($class as $key=>$value){
                    if($value['classname'] == $class_name){
                        $class_html .= '<option value='.$value["classname"].' selected>'.$value["classname"].'</option>';
                    }else{
                        $class_html .= '<option value='.$value["classname"].'>'.$value["classname"].'</option>';
                    }

                }
            }
            //年级类型
            $school_where['schoolid'] = $school_id;
            $school_type = db('school')->field('typeid')->where($school_where)->find();
            if(!empty($school_type)){
                $type = explode(',',$school_type['typeid']);
                $grade = array();
                foreach($type as $key=>$val){
                    $grade[]= db('schooltype')->field('sc_id,sc_type')->where('sc_id = "'.$val.'"')->order('sc_sort ASC')->find();
                }
//                halt($grade);
                if(!empty($grade)){
                    foreach($grade as $key=>$value){
                        if($value['sc_type'] == $grade_name){
                            $grade_html .= '<option value='.$value["sc_type"].' selected> '.$value["sc_type"].'</option>';
                        }else{
                            $grade_html .= '<option value='.$value["sc_type"].'>'.$value["sc_type"].'</option>';
                        }

                    }
                }
            }

        }

        exit(json_encode(array('grade'=>$grade_html,'class'=>$class_html)));
    }

    /**
     * @desc 根据学校id获取年级和班级
     * @author langzhiyao
     * @time 20180927
     */
    public function get_school_infos(){
        $school_id = intval(input('get.school'));
        $grade_name = trim(input('get.grade'));//年级类型ID
        $class_name = trim(input('get.class'));//年级类型ID


        $grade_html = '<option value="0">请选择年级</option>';
        $class_html = '<option value="0">请选择班级</option>';
        if(!empty($school_id)){
            //班级
            $grade_where['schoolid'] = $school_id;
            $grade_class = db('schooltype')->field('sc_id,sc_type')->where(' `sc_id` = "'.$grade_name.'"')->find();
            $grade_where['typeid'] = $grade_class['sc_id'];
            $class =  db('class')->field('classid,classname')->where($grade_where)->select();
            if(!empty($class)){
                foreach($class as $key=>$value){
                    if($value['classname'] == $class_name){
                        $class_html .= '<option value='.$value["classid"].' selected>'.$value["classname"].'</option>';
                    }else{
                        $class_html .= '<option value='.$value["classid"].'>'.$value["classname"].'</option>';
                    }

                }
            }
            //年级类型
            $school_where['schoolid'] = $school_id;
            $school_type = db('school')->field('typeid')->where($school_where)->find();
            if(!empty($school_type)){
                $type = explode(',',$school_type['typeid']);
                $grade = array();
                foreach($type as $key=>$val){
                    $grade[]= db('schooltype')->field('sc_id,sc_type')->where('sc_id = "'.$val.'"')->order('sc_sort ASC')->find();
                }
//                halt($grade);
                if(!empty($grade)){
                    foreach($grade as $key=>$value){
                        if($value['sc_type'] == $grade_name){
                            $grade_html .= '<option value='.$value["sc_id"].' selected> '.$value["sc_type"].'</option>';
                        }else{
                            $grade_html .= '<option value='.$value["sc_id"].'>'.$value["sc_type"].'</option>';
                        }

                    }
                }
            }

        }

        exit(json_encode(array('grade'=>$grade_html,'class'=>$class_html)));
    }
    /**
     * @desc 根据会员ID 获取代理商ID
     * @author langzhiyao
     * @time 20180928
     */
    function get_agent($admin_id){
        //根据会员ID 获取会员角色
        $company = db('admin')->field('admin_company_id')->where('admin_id = '.$admin_id.'')->find();
        $data = '';
        if($company['admin_company_id'] == 1){
            $data = array('agent_id'=>1,'agent_name'=>'总公司');
        }else{
            $res = db('company')->field('o_id,o_name,o_del')->where('o_del = 1')->find();
            if($res){
                $data = array('agent_id'=>$res['o_id'],'agent_name'=>$res['o_name']);
            }
        }
        return $data;

    }



    /**
     * @desc 根据ID 获取地址名称
     * @author langzhiyao
     * @time 20180928
     */
    function get_address_name($id){

        $address_info = db('area')->field('area_id,area_name')->where('area_id= '.$id.'')->find();

        return $address_info['area_name'];

    }


    /**
     * @desc 根据省市区联动及学校信息
     * @author langzhiyao
     * @time 20180927
     */
    public function get_company_role(){
        $company_id = intval(input('get.company_id'));
        $role_id = intval(input('get.role_id'));
        $role_html = '<option value="0">请选择角色</option>';
        if(!empty($company_id)){
            //角色
            $where['company_id'] = $company_id;
            if($company_id == 1){
                $role = db('gadmin')->field('gid,gname')->select();
            }else{
                $role = db('gadmin')->field('gid,gname')->where($where)->select();
            }
            if(!empty($role)){
                foreach($role as $key=>$value){
                    if($value['gid'] == $role_id){
                        $role_html .='<option value='.$value["gid"].' selected>'.$value["gname"].'</option>';
                    }else{
                        $role_html .='<option value='.$value["gid"].'>'.$value["gname"].'</option>';
                    }

                }
            }
        }

        exit(json_encode(array('role'=>$role_html)));
    }


    /**
     * @desc 判断家长手机号是否存在
     * @author langzhiyao
     * @time 20181114
     */
    public function is_member_mobile(){
        $mobile = input('get.mobile');
        $m_id = input('get.m_id');
        if($m_id){
            $result = db('member')->field('member_id')->where('member_mobile="'.$mobile.'" AND member_id != "'.$m_id.'"')->find();
        }else{
            $result = db('member')->field('member_id')->where('member_mobile="'.$mobile.'"')->find();
        }
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc 判断学生身份证号是否存在
     * @author langzhiyao
     * @time 20181114
     */
    public function is_student_card(){
        $card = trim(input('get.card'));
        $s_id = input('get.s_id');
        if($s_id){
            $result = db('student')->field('s_id')->where('s_card="'.$card.'" AND s_id != "'.$s_id.'"')->find();
        }else{
            $result = db('student')->field('s_id')->where('s_card="'.$card.'"')->find();
        }
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc 根据学校id获取年级和班级
     * @author langzhiyao
     * @time 20180927
     */
    public function get_import_school_info(){
        $school_id = intval(input('get.school'));
        $grade_id = trim(input('get.grade'));//年级类型ID
        $class_id = trim(input('get.class'));//年级类型ID


        $grade_html = '<option value="0">请选择学校类型</option>';
        $class_html = '<option value="0">请选择班级</option>';
        if(!empty($school_id)){
            /*if(!empty($grade_id)){

            }*/
            //班级
            $grade_where['schoolid'] = $school_id;
            $grade_class = db('schooltype')->field('sc_id,sc_type')->where(' `sc_id` = "'.$grade_id.'"')->find();
            $grade_where['typeid'] = $grade_class['sc_id'];
            $class =  db('class')->field('classid,classname')->where($grade_where)->select();
            if(!empty($class)){
                foreach($class as $key=>$value){
                    if($value['classid'] == $class_id){
                        $class_html .= '<option value='.$value["classid"].' selected>'.$value["classname"].'</option>';
                    }else{
                        $class_html .= '<option value='.$value["classid"].'>'.$value["classname"].'</option>';
                    }

                }
            }
            //年级类型
            $school_where['schoolid'] = $school_id;
            $school_type = db('school')->field('typeid')->where($school_where)->find();
            if(!empty($school_type)){
                $type = explode(',',$school_type['typeid']);
                $grade = array();
                foreach($type as $key=>$val){
                    $grade[]= db('schooltype')->field('sc_id,sc_type')->where('sc_id = "'.$val.'"')->order('sc_sort ASC')->find();
                }
//                halt($grade);
                if(!empty($grade)){
                    foreach($grade as $key=>$value){
                        if($value['sc_id'] == $grade_id){
                            $grade_html .= '<option value='.$value["sc_id"].' selected> '.$value["sc_type"].'</option>';
                        }else{
                            $grade_html .= '<option value='.$value["sc_id"].'>'.$value["sc_type"].'</option>';
                        }

                    }
                }
            }

        }

        exit(json_encode(array('grade'=>$grade_html,'class'=>$class_html)));
    }


    /**
     * @desc 判断渠道名称是否存在
     * @author langzhiyao
     * @time 20181114
     */
    public function is_channel_name(){
        $channel_name = trim(input('get.channel_name'));
        $id = input('get.id');
        if($id){
            $result = db('channel')->field('id')->where('channel_name="'.$channel_name.'" AND id != "'.$id.'"')->find();
        }else{
            $result = db('channel')->field('id')->where('channel_name="'.$channel_name.'"')->find();
        }
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc 判断渠道标识是否存在
     * @author langzhiyao
     * @time 20181114
     */
    public function is_channel(){
        $channel = trim(input('get.channel'));
        $id = input('get.id');
        if($id){
            $result = db('channel')->field('id')->where('channel="'.$channel.'" AND id != "'.$id.'"')->find();
        }else{
            $result = db('channel')->field('id')->where('channel="'.$channel.'"')->find();
        }
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc  apk上传
     * @author langzhiyao
     * @time 20181121
     */
    public function apk_file_upload()
    {
        $file = request()->file('file'); // 获取上传的文件
        if($file==null){
            exit(json_encode(array('code'=>1,'msg'=>'未上传文件')));
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if(!in_array($extension,array("apk"))){
            exit(json_encode(array('code'=>1,'msg'=>'上传文件类型不合法')));
        }
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'apk');
        // 移动文件到指定目录 没有则创建
        $file = UPLOAD_SITE_URL.DS.'apk'.DS.$info->getSaveName();

        exit(json_encode(array('code'=>0,'msg'=>$file)));
    }

    /**
     * @desc 根据学校ID获取学校信息
     * @author langzhiyao
     * @time 20181212
     */
    public function get_school(){
        $school_id = intval(input('get.school'));
        $result = '';
        if(!empty($school_id)){
            $result = db('school')->where('schoolid="'.$school_id.'"')->find();
        }
        echo json_encode(array('info'=>$result));exit;
    }



}