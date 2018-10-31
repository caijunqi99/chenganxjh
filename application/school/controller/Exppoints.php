<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/12
 * Time: 17:21
 */

namespace app\school\controller;

use think\Lang;

class Exppoints extends AdminControl
{
    const EXPORT_SIZE = 5000;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'school/lang/zh-cn/Membergrade.lang.php');
    }
    /**
     * 设置经验值获取规则
     */
    public function expsetting(){
        $model = Model('config');
        if (request()->isPost()){
            $exp_arr = array();
            $exp_arr['exp_login'] = intval($_POST['exp_login'])?$_POST['exp_login']:0;
            $exp_arr['exp_comments'] = intval($_POST['exp_comments'])?$_POST['exp_comments']:0;
            $exp_arr['exp_orderrate'] = intval($_POST['exp_orderrate'])?$_POST['exp_orderrate']:0;
            $exp_arr['exp_ordermax'] = intval($_POST['exp_ordermax'])?$_POST['exp_ordermax']:0;
            $result = $model->updateConfig(array('exppoints_rule'=>serialize($exp_arr)));
            if ($result === true){
                $this->log(lang('ds_edit').lang('nc_exppoints_manage').lang('nc_exppoints_setting'),1);
                $this->success(lang('ds_common_save_succ'));
            }else {
                $this->error(lang('ds_common_save_fail'));
            }
        }
        $list_setting = $model->getRowConfig('exppoints_rule');
        $list_setting = unserialize($list_setting['value']);
        $this->assign('list_setting',$list_setting);
        $this->setAdminCurItem('expset');
        return $this->fetch();
    }


    /**
     * 积分日志列表
     */
    public function index(){
        $where = array();
        $search_mname = trim(input('param.mname'));
        $where['exp_membername'] = array('like',"%{$search_mname}%");
        if (input('param.stage')){
            $where['exp_stage'] = trim(input('param.stage'));
        }
        $stime = input('param.stime')?strtotime(input('param.stime')):0;
        $etime = input('param.etime')?strtotime(input('param.etime')):0;
        if ($stime > 0 && $etime>0){
            $where['exp_addtime'] = array('between',array($stime,$etime));
        }elseif ($stime > 0){
            $where['exp_addtime'] = array('egt',$stime);
        }elseif ($etime > 0){
            $where['exp_addtime'] = array('elt',$etime);
        }
        $search_desc = trim(input('param.description'));
        $where['exp_desc'] = array('like',"%$search_desc%");

        //查询积分日志列表
        $model = Model('exppoints');
        $list_log = $model->getExppointsLogList($where, '*', 20, 0, 'exp_id desc');
        //信息输出
        
        $this->assign('stage_arr',$model->getStage());
        $this->assign('show_page',$model->page_info->render());
        $this->assign('list_log',$list_log);
        $this->setAdminCurItem('explog');
        return $this->fetch();
    }



    /**
     * 积分日志列表导出
     */
    public function export_step1(){
        $where = array();
        $search_mname = trim(input('param.mname'));
        $where['exp_membername'] = array('like',"%{$search_mname}%");
        if (input('param.stage')){
            $where['exp_stage'] = trim(input('param.stage'));
        }
        $stime = input('param.stime')?strtotime(input('param.stime')):0;
        $etime = input('param.etime')?strtotime(input('param.etime')):0;
        if ($stime > 0 && $etime>0){
            $where['exp_addtime'] = array('between',array($stime,$etime));
        }elseif ($stime > 0){
            $where['exp_addtime'] = array('egt',$stime);
        }elseif ($etime > 0){
            $where['exp_addtime'] = array('elt',$etime);
        }
        $search_desc = trim(input('param.description'));
        $where['exp_desc'] = array('like',"%$search_desc%");

        //查询积分日志列表
        $model = Model('exppoints');
        $list_log = $model->getExppointsLogList($where, '*', self::EXPORT_SIZE, 0, 'exp_id desc');
        if (!is_numeric(input('param.curpage'))){
            $count = $model->getExppointsLogCount($where);
            $array = array();
            if ($count > self::EXPORT_SIZE ){	//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                $this->assign('list',$array);
                return $this->fetch('export.excel');
            }else{	//如果数量小，直接下载
                $this->createExcel($list_log);
            }
        }else{	//下载
            $this->createExcel($list_log);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        $excel_obj = new \excel\Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'经验值');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'添加时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'操作阶段');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'描述');
        $stage_arr = Model('exppoints')->getStage();
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['exp_membername']);
            $tmp[] = array('format'=>'Number','data'=>dsPriceFormat($v['exp_points']));
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['exp_addtime']));
            $tmp[] = array('data'=>$stage_arr[$v['exp_stage']]);
            $tmp[] = array('data'=>$v['exp_desc']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('经验值明细',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('经验值明细',CHARSET).input('param.curpage').'-'.date('Y-m-d-H',time()));
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'explog',
                'text' => '经验值明细',
                'url' =>  url('Exppoints/index')
            ),
            array(
                'name' => 'expset',
                'text' => '规则设置',
                'url' =>  url('Exppoints/expsetting')
            ),
        );
        return $menu_array;
    }
}