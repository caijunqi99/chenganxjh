<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Teacherhome extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    //教孩搜索栏菜单
    public function index(){
        //所有分类
        $model_type = model('Teachtype');
        $tmp_list = $model_type->getTreeTypeList(4);
        //一级分类
        $parentType = db('teachtype')->where(array('gc_parent_id'=>0))->select();
        //二级分类
        foreach($parentType as $key=>$item){
            foreach($tmp_list as $k=>$v){
                if($v['gc_parent_id']==$item['gc_id'] && $v['deep']==2){
                    $parentType[$key]['childTwo'][] = $v;
                }
            }
        }
        //三级分类
        foreach($parentType as $key=>$item){
            foreach($item['childTwo'] as $k2=>$v2){
                foreach($tmp_list as $k=>$v){
                    if($v['gc_parent_id']==$v2['gc_id'] && $v['deep']==3){
                        $item['childTwo'][$k2]['childThree'][] = $v;
                    }
                }
            }
            $parentType[$key]['childTwo'] =  $item['childTwo'];
        }
        //四级分类
        foreach($parentType as $key=>$item){
            foreach($item['childTwo'] as $k2=>$v2){
                foreach($v2['childThree'] as $k3=>$v3){
                    foreach($tmp_list as $k=>$v){
                        if($v['gc_parent_id']==$v3['gc_id'] && $v['deep']==4){
                            $v2['childThree'][$k3]['childFour'][] = $v;
                        }
                    }
                    if($v2['childThree'][$k3]['childFour']){
                        $item['type'] = 4;
                    }
                }
                $item['childTwo'][$k2]['childThree'] = $v2['childThree'];
            }
            $parentType[$key] =  $item;
        }
        $data = array();
        $data['navigate'] = [
            "subsume" => ["name"=>"综合","child"=>["综合","价格最高","价格最低"]],
            'free' => ["name"=>"查看免费"],
            'fees' => ["name"=>"查看付费"],
            "select" => ['name'=>"筛选"]
        ];
        $data['categorize'] = $parentType;
        $data['categorize'][] = array("gc_name"=>"推荐","childTwo"=>[]);
        //视频价格范围
        $pkg = model('pkgs');
        $conditions = array();
        $conditions['pkg_type']= 3 ;
        $pkg_list = $pkg->getPkgLists($conditions,'pkg_id,pkg_price','pkg_price asc');
        $data['price'] = $pkg_list;
        $res[] = $data;
        output_data($res);
    }

    //教孩列表页面
    public function lists(){
        $teachchild = model('Teachchild');

        $page = !empty(input('post.page')) ? input('post.page'): 1;
        $where = "t_audit=3 and t_del=1";
        if(!empty(input('post.type'))) {
            $where .= " and t_type=".input('post.type');
        }
        if(!empty(input('post.type1'))){
            $where .= " and t_type2=".input('post.type1');
        }
        if(!empty(input('post.type2'))){
            $where .= " and t_type3=".input('post.type2');
        }
        if(!empty(input('post.type3'))){
            $where .= " and t_type4=".input('post.type3');
        }
        if(input('post.recommend')&&input('post.recommend')==2){//推荐
            $where .= " and t_recommend=".input('post.recommend');
        }
        if(!empty(input('post.price_free'))){
            $where .= " and t_price=0";
        }
        if(!empty(input('post.price_fees'))){
            $where .= " and t_price!=0";
        }
        $title = input('post.title');//知识点（标题）
        if ($title) {
            $where .= " and t_title like '%".$title."%'";
        }
        if(input('post.subsume')&&input('post.subsume')==1){//综合
            $order='t_maketime desc,t_id desc';
        }
        if(!empty(input('post.price_desc'))){
            $order='t_price desc,t_id desc';
        }
        if(!empty(input('post.price_asc'))){
            $order='t_price asc,t_id desc';
        }
        if(empty($order)){
            $order .= "t_id desc";
        }
        $list = $teachchild->getPageTeachildList($where ,$order,$page);
        $path = "http://".$_SERVER['HTTP_HOST']."/uploads/";
        foreach($list as $k=>$v){
            if($v['t_picture']!=""){
                $list[$k]['t_picture'] = $path.$v['t_picture'];
            }
        }
        if($list){
            output_data($list);
        }else{
            $list = [];
            output_data($list);
        }
    }

}