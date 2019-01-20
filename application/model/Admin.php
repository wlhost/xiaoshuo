<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/1/15
 * Time: 14:45
 */

namespace app\model;


use think\Model;

class Admin extends Model
{
    protected $pk='id';
    protected $autoWriteTimestamp = true;

    public function setUsernameAttr($value){
        return trim($value);
    }

    public function setPasswordAttr($value){
        return md5(strtolower(trim($value)));
    }
}