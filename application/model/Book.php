<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/14
 * Time: 5:09 PM
 */

namespace app\model;


use think\Model;

class Book extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public static function init()
    {
        self::event('after_insert', function ($book) {
            cache('homepage_hot_pc',null);
            cache('homepage_hot_mobile',null);
            cache('homepage_newest_pc',null);
            cache('homepage_newest_mobile',null);
            cache('newest_week',null);
            cache('newest_month',null);
            cache('newest',null);
            cache('update_week',null);
            cache('update_month',null);
            cache('rank_update',null);
        });

        self::event('after_update', function ($book){
            cache('book'.$book->id,null);
        });
    }

    public function author()
    {
        return $this->belongsTo('author');
    }

    public function chapters()
    {
        return $this->hasMany('chapter');
    }

    public function setBookNameAttr($value)
    {
        return trim($value);
    }

    public function setSummaryAttr($value){
        return trim($value);
    }

    public function setSrcAttr($value){
        if (is_null($value) || empty($value)){
            return '手动';
        }
    }
}