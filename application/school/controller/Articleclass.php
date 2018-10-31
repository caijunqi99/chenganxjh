<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 14:45
 */

namespace app\school\controller;


use think\Lang;
use think\Validate;

class Articleclass extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH.'school/lang/zh-cn/articleclass.lang.php');
    }
    /**
     * 文章管理
     */
    public function index(){
        $model_class = Model('articleclass');
        //删除
        if (request()->isPost()){
            if (!empty($_POST['check_ac_id'])){
                if (is_array($_POST['check_ac_id'])){
                    $del_array = $model_class->getChildClass($_POST['check_ac_id']);
                    if (is_array($del_array)){
                        foreach ($del_array as $k => $v){
                            $model_class->del($v['ac_id']);
                        }
                    }
                }
                $this->log(lang('ds_del').lang('article_class_index_class'),1);
                $this->success(lang('article_class_index_del_succ'));
            }else {
                $this->error(lang('article_class_index_choose'));
            }
        }
        /**
         * 父ID
         */
        $parent_id = input('param.ac_parent_id')?intval(input('param.ac_parent_id')):0;
        /**
         * 列表
         */
        $tmp_list = $model_class->getTreeClassList(2);
        $class_list=array();
        if (is_array($tmp_list)){
            foreach ($tmp_list as $k => $v){
                if ($v['ac_parent_id'] == $parent_id){
                    /**
                     * 判断是否有子类
                     */
                    $v['have_child'] = 0;
                    if (@$tmp_list[$k+1]['deep'] > $v['deep']){
                        $v['have_child'] = 1;
                    }
                    $class_list[] = $v;
                }
            }
        }
        if (input('param.ajax') == '1'){
            /**
             * 转码
             */
            $output = json_encode($class_list);
            print_r($output);
            exit;
        }else {
           $this->assign('class_list',$class_list);
            $this->setAdminCurItem('index');
            return $this->fetch('article_class_index');
        }
    }

    /**
     * 文章分类 新增
     */
    public function article_class_add(){
        $model_class = Model('articleclass');
        if (request()->isPost()){
            /**
             * 验证
             */
            $data=[
                'ac_name'=>input('param.ac_name'),
                'ac_sort'=>input('param.ac_sort')
            ];
            $rule=[
                ['ac_name','require',lang('article_class_add_name_null')],
                ['ac_sort','number',lang('article_class_add_sort_int')]
            ];
            $obj_validate = new Validate();
            $validate_result=$obj_validate->check($data,$rule);
            if(!$validate_result){
                $this->error($obj_validate->getError());
            }else {

                $insert_array = array();
                $insert_array['ac_name'] = trim(input('param.ac_name'));
                $insert_array['ac_parent_id'] = intval(input('param.ac_parent_id'));
                $insert_array['ac_sort'] = trim(input('param.ac_sort'));

                $result = $model_class->add($insert_array);
                if ($result){
                    $this->log(lang('ds_add').lang('article_class_index_class').'['.$_POST['ac_name'].']',1);
                    $this->success(lang('article_class_add_succ'),'articleclass/index');
                }else {
                    $this->error(lang('article_class_add_fail'));
                }
            }
        }
        /**
         * 父类列表，只取到第三级
         */
        $parent_list = $model_class->getTreeClassList(1);
        if (is_array($parent_list)){
            foreach ($parent_list as $k => $v){
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['ac_name'];
            }
        }

       $this->assign('ac_parent_id',intval(input('param.ac_parent_id')));
       $this->assign('parent_list',$parent_list);
       $this->setAdminCurItem('add');
        return $this->fetch('article_class_add');
    }

    /**
     * 文章分类编辑
     */
    public function article_class_edit(){

        $model_class = Model('articleclass');

        if (request()->isPost()){
            /**
             * 验证
             */
            $data=[
                'ac_name'=>input('param.ac_name'),
                'ac_sort'=>input('param.ac_sort')
            ];
            $rule=[
                ['ac_name','require',lang('article_class_add_name_null')],
                ['ac_sort','number',lang('article_class_add_sort_int')]
            ];
            $obj_validate = new Validate();
            $validate_result=$obj_validate->check($data,$rule);
            if(!$validate_result){
                $this->error($obj_validate->getError());
            }else {

                $update_array = array();
                $update_array['ac_id'] = intval($_POST['ac_id']);
                $update_array['ac_name'] = trim($_POST['ac_name']);
                $update_array['ac_sort'] =trim($_POST['ac_sort']);

                $result = $model_class->update($update_array);
                if ($result){
                    $this->log(lang('ds_edit').lang('article_class_index_class').'['.$_POST['ac_name'].']',1);
                    $this->success(lang('article_class_edit_succ'),'articleclass/index');
                }else {
                   $this->error(lang('article_class_edit_fail'));
                }
            }
        }

        $class_array = $model_class->getOneClass(intval(input('param.ac_id')));
        if (empty($class_array)){
           $this->error(lang('param_error'));
        }

       $this->assign('class_array',$class_array);
        $this->setAdminCurItem('edit');
        return $this->fetch('article_class_edit');
    }

    /**
     * 删除分类
     */
    public function article_class_del(){
        $model_class = Model('articleclass');
        if (intval(input('param.ac_id')) > 0){
            $array = array(intval(input('param.ac_id')));

            $del_array = $model_class->getChildClass($array);
            if (is_array($del_array)){
                foreach ($del_array as $k => $v){
                    $model_class->del($v['ac_id']);
                }
            }
            $this->log(lang('ds_add').lang('article_class_index_class').'[ID:'.intval(input('param.ac_id')).']',1);
            $this->success(lang('article_class_index_del_succ'),'articleclass/index');
        }else {
            $this->success(lang('article_class_index_choose'),'articleclass/index');
        }
    }
    /**
     * ajax操作
     */
    public function ajax(){
        switch (input('param.branch')){
            /**
             * 分类：验证是否有重复的名称
             */
            case 'article_class_name':
                $model_class = Model('articleclass');
                $class_array = $model_class->getOneClass(intval(input('param.id')));

                $condition['ac_name'] = trim(input('param.value'));
                $condition['ac_parent_id'] = $class_array['ac_parent_id'];
                $condition['no_ac_id'] = intval(input('param.id'));
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)){
                    $update_array = array();
                    $update_array['ac_id'] = intval(input('param.id'));
                    $update_array['ac_name'] = trim(input('param.value'));
                    $model_class->update($update_array);
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
            /**
             * 分类： 排序 显示 设置
             */
            case 'article_class_sort':
                $model_class = Model('article_class');
                $update_array = array();
                $update_array['ac_id'] = intval(input('param.id'));
                $update_array[input('param.column')] = trim(input('param.value'));
                $result = $model_class->update($update_array);
                echo 'true';exit;
                break;
            /**
             * 分类：添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = Model('articleclass');
                $condition['ac_name'] = trim(input('param.ac_name'));
                $condition['ac_parent_id'] = intval(input('param.ac_parent_id'));
                $condition['no_ac_id'] = intval(input('param.ac_id'));
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)){
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }

    protected function getAdminItemList()
    {
        $menu_array=array(
            array('name'=>'index','text'=>'管理','url'=>url('articleclass/index')),
            array('name'=>'add','text'=>'新增','url'=>url('articleclass/article_class_add'))
        );
        if(request()->action() == 'article_class_edit'){
            $menu_array[]=array('name'=>'edit','text'=>'编辑','url'=>url('articleclass/edit'));
        }
        return $menu_array;
    }
}