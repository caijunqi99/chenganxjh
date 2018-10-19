<?php
namespace app\wap\controller;

use think\Lang;
use process\Process;

class Goods extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');

    }

    /**
     * @desc 商城首页
     * @author langzhiyao
     * @time 20181019
     */
    public function index(){


        $upload_file = UPLOAD_SITE_URL . DS . ATTACH_ADV.'/';
        $upload_file2 = UPLOAD_SITE_URL . DS . ATTACH_GOODS.'/';
//        UPLOAD_SITE_URL
        //获取轮播图
        $banner = db('adv')->field('adv_title,adv_link,adv_code')->where('ap_id =16 AND adv_enabled=1 AND is_show=1')->order('adv_sort asc')->select();
        if(!empty($banner)){
            foreach($banner as $k=>$v){
                $banner[$k]['adv_code'] = $upload_file.$v['adv_code'];
            }
        }

        //获取类别
        $type = db('goodsclass')->field('gc_id,gc_name')->where('gc_show =1 AND gc_parent_id=0')->order('gc_sort asc')->select();
        if(!empty($type)){
            foreach ($type as $ke=>$va) {
                $type[$ke]['link'] =BASE_SITE_URL. '/tmpl/product_list.html?b_id='.$va['gc_id'];
             }
        }
        //获取第一版广告位
        $gg_one = db('adv')->field('adv_title,adv_link,adv_code')->where('ap_id =17 AND adv_enabled=1 AND is_show=1')->order('adv_sort asc')->find();
        if(!empty($gg_one)){
            $gg_one['adv_code'] = $upload_file.$gg_one['adv_code'];
        }
        //获取商品
        $goods = db('goodscommon')->field('goods_commonid,goods_name,goods_image,goods_price,goods_marketprice')->order('goods_commend asc')->limit(0,4)->select();
        if(!empty($goods)){
            foreach($goods as $key=>$val){
                $goods[$key]['goods_image'] = $upload_file2.$val['goods_image'];
            }
        }
        $result[] =array('gg'=>$gg_one,'cp'=>$goods);
        $arr[] =array('lb'=>$banner,'type'=>$type,'goods'=>$result);

        output_data($arr);

    }


}