<?php

namespace app\school\controller;

use think\Lang;
use think\Validate;

class Stest extends AdminControl {

	public $SchoolInsertInfo;
	public $SchoolInsert;
	public $ClassInsertInfo;
	public $ClassInsert;
	public $True_School;
	public $True_Class;
	function __construct(){
		$this->SchoolInsertInfo = [];
		$this->SchoolInsert = [];
		$this->ClassInsertInfo = [];
		$this->ClassInsert = [];
		$this->True_School = [];
		$this->True_Class = [];
    	exit;
	}

	public function StartTrans(){
		@set_time_limit(0);
    	$this->SchoolTrans();
	}

	/**
	 * 学校导入
	 * @创建时间 2018-11-03T22:48:56+0800
	 */
    public function SchoolTrans(){
        $starttime = explode(' ',microtime());

		echo '学校录入开始----------------------------------------------------------<br>';
    	//真实幼儿园数据
	    $oldschool = array(
	    	array(
	    		'name'=>'太谷二中启航',
	    		'region_province_id'=> '000000005e41e11b015e45eed6340007'
	    	),
			array(
				'name'=>'嘉禾幼儿园',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'金宝国际幼儿园',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'恒大绿州幼儿园',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'财大新秀双语幼儿园',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'太原龙之源',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'常乐幼儿园',
				'region_province_id'=> '000000005e41e11b015e45eed6340007'
			),
			array(
				'name'=>'天天向上幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'红星凯悦圆梦幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'红星凯悦艺童幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'零工厂教育机构',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'智鑫幼稚园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'优咪早教亲子幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'博格恩幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'哈尔滨市道外区博艺幼稚园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'红旗小区馨视界幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'欣梦圆教育',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'亲亲宝贝幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'希望启智幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'优佳幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'育才幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'新浪潮艺术教育机构',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'光明村阳光宝贝幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'山水幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'金色童年幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'晨曦幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'朵朵乐幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'新希望幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1641daa0295'
			),
			array(
				'name'=>'龙凤幼儿园一园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16917ba02a5'
			),
			array(
				'name'=>'龙凤幼儿园三园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16917ba02a5'
			),
			array(
				'name'=>'龙凤幼儿园二园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16917ba02a5'
			),
			array(
				'name'=>'何仙幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16917ba02a5'
			),
			array(
				'name'=>'成长印记幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16460fb0297'
			),
			array(
				'name'=>'庄河育蕾幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16460fb0297'
			),
			array(
				'name'=>'新育龙幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb16460fb0297'
			),
			array(
				'name'=>'周口冠博学校',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'周口市实验中学第一附属幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'实验中学第二附属幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'实验中学第三附属幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'艾菲儿双语幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'郸城县秋霞个协幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'郸城北关个协幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb1627abb028f'
			),
			array(
				'name'=>'武南镇小精灵幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'博睿幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'凉州区阳光一代幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'金色摇篮幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'凉州幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'小灵童幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'荣华幼儿园',
				'region_province_id'=> 'bbb1af9e5eaca9ad015eb165fe99029c'
			),
			array(
				'name'=>'南湖体育馆幼儿园',
				'region_province_id'=> '8a9e3e295e36483c015e368560360008'
			),
			array(
				'name'=>'美育教育中心新加坡分园',
				'region_province_id'=> '8a9e3e295e36483c015e368560360008'
			),
			array(
				'name'=>'戴氏教育',
				'region_province_id'=> '8a9e3e295e36483c015e368560360008'
			),
			array(
				'name'=>'远达美蒙幼稚园',
				'region_province_id'=> '8a9e3e295e36483c015e368560360008'
			),
			array(
				'name'=>'钻井大队幼儿园',
				'region_province_id'=> '8a9e3e295e36483c015e368560360008'
			),
	    );
	    $Sclist = [];
	    $ScError = [];
	    foreach ($oldschool as $key => $v) {
	    	$data = db('aaschool')->where($v)->find();
	    	if($data){
	    		$Sclist[] = $data;
	    	}else{
	    		$ScError[] = $v;
	    	}
	    }
	    if ($ScError) {
	    	echo '未查询到此数据！';
	    	p($ScError);exit;
	    }
	    /**
	     * 存储对应学校信息
	     * @var [type]
	     */
        $model_school = model('School');
	    $this->SchoolInsertInfo =[];
    	$this->SchoolInsert = [];
	    foreach ($Sclist as $key => $school) {
	    	$oldarea = db('aaregion')->where('id',$school['region_town_id'])->value('name');

	    	$area = db('area')->where('area_name','like',$oldarea)->field('area_id,area_mergename,area_parent_id')->find();
	    	$city = db('area')->where('area_id',$area['area_parent_id'])->field('area_id,area_parent_id')->find();
	    	$province = db('area')->where('area_id',$city['area_parent_id'])->field('area_shortname,area_id')->find();
            $uniqueCard = "";
	    	if($province['area_shortname']){
                for($i=0;$i<strlen($province['area_shortname']);$i=$i+3){
                    $uniqueCard .= $model_school->getFirstCharter(substr($province['area_shortname'],$i,3));
                }
            }
            $number = $model_school -> getNumber($uniqueCard);
	    	$schooData = array(
				'name'             => $school['name'],
				'provinceid'       => $city['area_parent_id'], // 省
				'cityid'           => $city['area_id'],	//市
				'areaid'           => $area['area_id'],	//县
				'region'           => $area['area_mergename'], // 详细省市县
				'typeid'           => 1,	//学校类型 1幼儿园 2小学 3初中 4高中  5培训学校
				'address'          => $school['address'], //学校地址
				'common_phone'     => '0',//电话
				'username'         => db('aauser')->where('id',$school['user_user_id'])->value('name'), //负责/联系人 性别
				'desc'             => $school['name'], //备注
				'createtime'       => $school['createTime'], //添加时间/
				'updatetime'       => date('Y-m-d H:i:s',time()), ///修改时间
				'isdel'            => 1, //是否删除 1未删除 2已删除
				'option_id'        => 1, //添加人id
				'admin_company_id' => 1, //公司id，代表此学校是何公司名下
				'res_group_id'     => 0, //res_group_id
				'schoolCard'	  => $uniqueCard.$number,
				'oldid'	  => $school['id'],
	    	);
	    	$this->True_School[] = $schooData;
	    	$result = $model_school->addSchool($schooData);
    		$this->SchoolInsertInfo[$k][$key]['schoolname']=$schooData['name'];
    		if ($result) {
    			echo "学校 【".$schooData['name']."】 录入成功<br>";
    			$this->SchoolInsertInfo[$k][$key]['insert']='TRUE';
    			$this->SchoolInsert[] = $result;
    		}else{
    			echo "学校 【".$schooData['name']."】 录入失败----------<br>";
    			$this->SchoolInsertInfo[$k][$key]['insert']='FALSE';
    			$this->SchoolInsert[] = 'FALSE';

    		}
			

	    }

	    $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		echo "学校录入耗时：".$thistime." 秒。".time().'<br>';
		echo "总共成功录入".count($this->SchoolInsert).'条。<br>';
		if (in_array('FALSE', $this->SchoolInsert)) {
			$fails = array_count_values($this->SchoolInsert);
			echo '错误数量：'.$fails['FALSE'].'个<br>';
		}
		echo '学校录入结束----------------------------------------------------------<br>';
    	$this->ClassTrans();

	    //插入学校信息
	    // db('school')->insertAll($this->True_School);
	    
    }

    /**
     * 班级录入
     * @创建时间 2018-11-03T22:49:05+0800
     */
    public function ClassTrans(){
    	$starttime = explode(' ',microtime());
    	echo '班级录入开始-----------------------------------------------------------------<br>';
    	$True_School = db('school')->where('oldid','not null')->select();
    	$model_classes = model('Classes');
    	$this->True_Class=[];
    	$this->ClassInsertInfo =[];
    	$this->ClassInsert = [];
    	foreach ($True_School as $k => $s) {
    		$oldClass = db('aaclassroom')->where('school_school_id',$s['oldid'])->select();
    		
    		if($oldClass)foreach ($oldClass as $key => $c) {
    			$classcard=$s['schoolCard'].($model_classes->getNumber($s['schoolCard']));
    			$qr = $this->MakeQr($classcard);
	    		$data = array(
					'classname'         => $c['name'], // 班级名称
					'classCard'         => $classcard, //班级标识号
					'schoolid'          => $s['schoolid'], //学校id
					'school_provinceid' => $s['provinceid'], //省
					'school_cityid'     => $s['cityid'], //市
					'school_areaid'     => $s['areaid'], //县
					'school_region'     => $s['region'], //地址
					'typeid'            => $s['typeid'], // 默认全是幼儿园的
					'desc'              => $s['desc'], // 班级备注
					'qr'                => $qr, //二维码地址
					'isdel'             => 1, //1未删除
					'createtime'        => $c['createTime'], //创建时间
					'updatetime'        => date('Y-m-d H:i:s',time()), ///修改时间
					'option_id'         => 1, 
					'admin_company_id'  => 1,
					'res_group_id'      => 0,
					'oldid'             => $c['id'],  //老数据班级id
	    		);

	    		$result = $model_classes->addClasses($data);
	    		$this->ClassInsertInfo[$k][$key]['classname']=$c['name'];
	    		if ($result) {
	    			echo "班级 【".$data['classname']."】 录入成功<br>";
	    			$this->ClassInsertInfo[$k][$key]['insert']='TRUE';
	    			$this->ClassInsert [] = $result;
	    		}else{
	    			echo "班级 【".$data['classname']."】 录入失败----------<br>";
	    			$this->ClassInsertInfo[$k][$key]['insert']='FALSE';
	    			$this->ClassInsert [] = 'FALSE';

	    		}
    			$this->True_Class[$k][$key]=$data;
    		}
    		
    	}
    	$endtime = explode(' ',microtime());
		$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		echo "执行写入耗时：".$thistime." 秒。".time().'<br>';
		echo "总共成功写入班级".count($this->ClassInsert).'条。<br>';
		if (in_array('FALSE', $this->ClassInsert)) {
			$fails = array_count_values($this->ClassInsert);
			echo '错误数量：'.$fails['FALSE'].'个<br>';
		}
    	echo '班级录入结束-----------------------------------------------------------------<br>';

		// p($this->ClassInsert);
    	// p($this->ClassInsertInfo);
    	// p($this->True_Class);
    }

    /**
     * 家长会员录入
     * @创建时间 2018-11-03T22:49:11+0800
     */
    public function MemberTrans(){
    	@set_time_limit(0);
    	$starttime = explode(' ',microtime());
    	echo '家长录入开始-----------------------------------------------------------------<br>';
    	//获取班级老数据
    	$field='id,name,nickname,loginName,memberType,idCard,password,phone,createTime,lastTime,lastLoginTime,address,school_school_id,classroom_classroom_id,region_province_id,region_city_id,region_town_id,sex';
    	$True_Class = db('class')->where('oldid','not null')->select();
    	$oldMember = db('aamember')->field($field)->where('name','notlike',['%测%','%test%'],'OR')->select();
    	$data = [];
    	$left_menu=array_column($True_Class, 'oldid');
    	
    	foreach ($oldMember as $key => $m) {
    		if(empty($m['phone'])) continue;
    		if(strlen($m['phone']) != 11) continue;
			$data[$key]['member_name']           = $m['name'];  //用户名称
			$data[$key]['member_nickname']       = !empty($m['nickname'])?$m['nickname']:(!empty($m['loginName'])?$m['loginName']:$m['name']); // 昵称
			$data[$key]['member_identity']       = $m['memberType']==0?2:1; // 身份
			$data[$key]['is_owner']              =  0; //是否主账号
			$data[$key]['member_age']            = 1; //年龄
			$data[$key]['member_truename']       = $m['name']; //真实姓名
			$data[$key]['member_idcard']         = $m['idCard']; //真实姓名
			$data[$key]['member_password']       = $m['password']; //密码 
			$data[$key]['member_mobile']         = $m['phone']; //手机号
			$data[$key]['member_mobile_bind']    = empty($m['phone'])?0:1; //是否绑定手机
			$data[$key]['member_add_time']       = empty($m['createTime'])?TIMESTAMP:strtotime($m['createTime']); //会员添加时间
			$data[$key]['member_edit_time']      = strtotime($m['lastTime']); //修改时间
			$data[$key]['member_old_login_time'] = strtotime($m['lastLoginTime']); //会员上次登录时间
			$data[$key]['oldid']                 = $m['id']; //老数据id
    		$k = array_search($m['classroom_classroom_id'], $left_menu);
    		if($k){//如果存在绑定学校
				$data[$key]['member_provinceid'] = $True_Class[$k]['school_provinceid']; //省
				$data[$key]['member_cityid']     = $True_Class[$k]['school_cityid']; //市
				$data[$key]['member_areaid']     = $True_Class[$k]['school_areaid']; //县
				$data[$key]['member_areainfo']   = !empty($m['address'])?$m['address']:$True_Class[$k]['school_region']; //地址
				$data[$key]['classid']           = $True_Class[$k]['classid']; //地址
    		}else{
    			$data[$key]['member_provinceid'] = ''; //省
				$data[$key]['member_cityid']     = ''; //市
				$data[$key]['member_areaid']     = ''; //县
				$data[$key]['member_areainfo']   = ''; //地址
				$data[$key]['classid']           = ''; //地址
    		}
    	}
    	$MemberInsertInfo = [];
    	$MemberInsert = [];
    	foreach ($data as $ke => $d) {
    		$result = db('member')->insertGetId($d);
    		if ($result) {
    			echo "会员用户 【".(empty($d['member_name'])?$d['member_nickname']:$d['member_name'])."】 录入成功<br>";
    			$MemberInsertInfo[$ke]['insert']='TRUE';
    			$MemberInsert [] = $result;
    		}else{
    			echo "会员用户 【".(empty($d['member_name'])?$d['member_nickname']:$d['member_name'])."】 录入失败----------<br>";
    			$MemberInsertInfo[$ke]['insert']='FALSE';
    			$MemberInsert [] = 'FALSE';
    		}
    	}
    	$endtime = explode(' ',microtime());
		$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		echo "执行写入耗时：".$thistime." 秒。".time().'<br>';
		echo "总共成功写入班级".count($MemberInsert).'条。<br>';
		if (in_array('FALSE', $MemberInsert)) {
			$fails = array_count_values($MemberInsert);
			echo '错误数量：'.$fails['FALSE'].'个<br>';
		}
    	echo '家长录入结束-----------------------------------------------------------------<br>';
    }

    /**
     * 学生录入
     * @创建时间 2018-11-03T22:49:26+0800
     */
    public function StudentTrans(){
    	@set_time_limit(0);
    	$starttime = explode(' ',microtime());
    	echo '学生录入开始-----------------------------------------------------------------<br>';
    	$True_Class = db('class')->where('oldid','not null')->select();
    	
    	$TrueStudent=[];
    	$memmmm =[];
    	foreach ($True_Class as $key => $c) {
    		$where = array(
    			'classroom_classroom_id'=>$c['oldid'],
    			// 'loginName' =>array('notlike',['%test%','%ceshi%','%测试%']),
    			// 'name' =>array('notlike',['%test%','%测试%','%ceshi%']),
    		);
    		$field = 'id,name,gender,birthday,idCard,guardianPhone,address,createTime,note';
    		$oldStudent = db('aastudent')->field($field)->where($where)->select();

    		if($oldStudent)foreach ($oldStudent as $k => $s) {
    			$oldmemberid=db('studentbindstudentmember')->where('student_bindStudentMember_id',$s['id'])->value('member_id');
    			$member_id=db('member')->where('oldid',$oldmemberid)->value('member_id');
    			if(!$member_id)$member_id = db('member')->where('member_mobile',$s['guardianPhone'])->value('member_id');	
    			if($member_id)$memmmm[]=$member_id;
    			$TrueStudent[]= array(
					's_name'         => $s['name'], //学生名字
					's_sex'          => $s['gender']==1?2:1, //性别：1，男；2，女
					's_classid'      => $c['classid'], //班级id
					's_schoolid'     => $c['schoolid'], //学校id
					's_sctype'       => 1, //学校类型id
					's_birthday'     => $s['birthday'], //生日
					's_card'         => $s['idCard'], //学生身份证号
					's_provinceid'   => $c['school_provinceid'], //省id
					's_cityid'       => $c['school_cityid'], //市
					's_areaid'       => $c['school_areaid'], //县
					's_region'       => empty($s['address'])?$c['school_region']:$s['address'], //地址
					's_createtime'   => $s['createTime'], //创建时间
					's_remark'       => $s['note'], //备注
					's_ownerAccount' => !empty($member_id)?$member_id:'', //学生绑定的家长账号id （主账户）
					'classCard'      => $c['classCard'], //班级识别码（app绑定学生时添加）
					'oldid'          => $s['id'], //老id
    			);
    		}
    	} 

    	$StudentInsertInfo = [];
    	$StudentInsert = [];
    	foreach ($TrueStudent as $key => $value) {
    		$result=db('student')->insertGetId($value);
    		if ($result) {

    			echo "学生 ID【".$result."】---姓名【".$value['s_name']."】 录入成功<br>";
    			$StudentInsertInfo[$key]['insert']='TRUE';
    			$StudentInsert [] = $result;
    		}else{
    			echo "学生 【".$value['s_name']."】 录入失败----------<br>";
    			$StudentInsertInfo[$key]['insert']='FALSE';
    			$StudentInsert [] = 'FALSE';
    		}
    	}

    	$endtime = explode(' ',microtime());
		$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		echo "执行写入耗时：".$thistime." 秒。".time().'<br>';
		echo "总共成功写入班级".count($StudentInsert).'条。<br>';
		if (in_array('FALSE', $StudentInsert)) {
			$fails = array_count_values($StudentInsert);
			echo '错误数量：'.$fails['FALSE'].'个<br>';
		}
    	echo '学生录入结束-----------------------------------------------------------------<br>';
    }

    /**
     * 订单录入
     * @创建时间 2018-11-04T21:30:35+0800
     */
    public function OrderTrans(){
    	@set_time_limit(0);
    	$starttime = explode(' ',microtime());
    	echo '订单录入开始-----------------------------------------------------------------<br>';
    	$True_Member = db('aaorder')
    					->alias('a')
    					->join('__AAORDERITEM__ m','m.order_order_id = a.id','RIGHT')
    	                // ->field('a.*,m.member_id,m.member_name,m.oldid,m.classid')
    	                ->select();
    	
    	$TrueStudent=[];
    	
    	p($True_Member);
    	// $OrderInsertInfo = [];
    	// $OrderInsert = [];
    	// foreach ($TrueStudent as $key => $value) {
    	// 	$result=db('student')->insertGetId($value);
    	// 	if ($result) {
    	// 		echo "学生 ID【".$result."】---姓名【".$value['s_name']."】 录入成功<br>";
    	// 		$OrderInsertInfo[$key]['insert']='TRUE';
    	// 		$OrderInsert [] = $result;
    	// 	}else{
    	// 		echo "学生 【".$value['s_name']."】 录入失败----------<br>";
    	// 		$OrderInsertInfo[$key]['insert']='FALSE';
    	// 		$OrderInsert [] = 'FALSE';
    	// 	}
    	// }

    	$endtime = explode(' ',microtime());
		$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		echo "执行写入耗时：".$thistime." 秒。".time().'<br>';
		// echo "总共成功写入班级".count($OrderInsert).'条。<br>';
		// if (in_array('FALSE', $OrderInsert)) {
		// 	$fails = array_count_values($OrderInsert);
		// 	echo '错误数量：'.$fails['FALSE'].'个<br>';
		// }
    	echo '订单录入结束-----------------------------------------------------------------<br>';
    }
    public function MakeQr($classcard){
    	//生成二维码
        import('qrcode.index',EXTEND_PATH);
        $PhpQRCode = new \PhpQRCode();
        $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_STORE . DS . 'class' . DS);
        // 生成班级二维码
        $PhpQRCode->set('date', $classcard);
        $PhpQRCode->set('pngTempName', $classcard . '.png');
        $qr=$PhpQRCode->init();
        $qr='/home/store/class/'.$qr;
        return $qr;
    }


}

?>
