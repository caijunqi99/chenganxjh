<?php

namespace app\common\model;

use think\Db;
use think\Model;

class ImportStudent extends Model
{

    public function insert(array $data = [], $replace = false, $getLastInsID = false, $sequence = null)
    {
        return parent::insert($data, $replace, $getLastInsID, $sequence); // TODO: Change the autogenerated stub
    }

    public function insertGetId(array $data, $replace = false, $sequence = null)
    {
        return parent::insertGetId($data, $replace, $sequence); // TODO: Change the autogenerated stub
    }

    public function autoRelationUpdate($relation)
    {
        parent::autoRelationUpdate($relation); // TODO: Change the autogenerated stub
    }

    public function delete()
    {
        return parent::delete(); // TODO: Change the autogenerated stub
    }


}