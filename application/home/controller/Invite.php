<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 14:30
 */

namespace app\home\controller;


class Invite extends BaseMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    public function index(){
        return $this->fetch($this->template_dir.'index');
    }
}