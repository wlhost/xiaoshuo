<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/1/19
 * Time: 12:18
 */

namespace app\model;


use think\Model;

class Category extends Model
{
    protected $pk='id';
    protected $autoWriteTimestamp = true;

    public function setCateNameAttr($value){
        return trim($value);
    }
}