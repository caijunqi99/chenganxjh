<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Companybanks extends AdminControl {

    //添加银行卡信息
    public function index(){
        if (!request()->isPost()) {
            //地区信息
            $region_list = db('area')->where('area_parent_id','0')->select();
            $this->assign('region_list', $region_list);
            $address = array(
                'true_name' => '',
                'area_id' => '',
                'city_id' => '',
                'address' => '',
                'tel_phone' => '',
                'mob_phone' => '',
                'is_default' => '',
                'area_info'=>''
            );
            $this->assign('address', $address);
            $this->setAdminCurItem('index');
            return $this->fetch();
        } else {
            $admininfo = $this->getAdminInfo();
            $company_banks = model('Companybanks');
            $data = array(
                'area' => input('post.area_id'),
                'bank_info' => input('post.bank_info'),
                'bank_name' => input('post.bank_name'),
                'bank_card' => input('post.bank_card'),
                'true_name' => input('post.true_name'),
                'default_mobile' => input('post.default_mobile'),
                'option_id' => $admininfo['admin_id'],
                'company_id' => $admininfo['admin_company_id'],
                'creattime' => time()
            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['city'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['province'] = $province_id['area_parent_id'];
            //验证数据  END
            $result = $company_banks->addBanks($data);
            if ($result) {
                $this->success("添加银行卡成功", 'Companybanks/index');
            } else {
                $this->error("添加银行卡失败");
            }
        }
    }

    protected function getAdminItemList() {
//        if(session('admin_is_super') !=1){
                $menu_array = array(
                    array(
                        'name' => 'index',
                        'text' => '添加',
                        'url' => url('Admin/Companybanks/index')
                    )
                );
//        }

        return $menu_array;
    }

}
