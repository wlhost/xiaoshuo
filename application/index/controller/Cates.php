<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/15
 * Time: 8:13 PM
 */

namespace app\index\controller;

use app\model\Category;
use app\service\BookService;
use think\Db;
use think\Request;

class Cates extends Base
{
    protected $bookService;
    protected function initialize()
    {
        parent::initialize();
        $this->bookService = new BookService();
    }

    public function index(Request $request){
        $cate = $request->param('cate');
        $map[] = ['id','>',0];
        if (!is_null($cate) && !empty($cate)){
            $map[] = ['category','like','%'.$cate.'%'];
        }
        $end = $request->param('end');
        if (!is_null($end) && !empty($end)){
            $map[] = ['end','=',$end];
        }
        $order = is_null($request->param('order'))?'last_time':$request->param('order');
        $num = 28;
        if (isMobile()){
            $num = 9;
        }
        $books = $this->bookService->getPagedBooks($map,$order,$num);
        $cates = cache('cates');
        if (!$cates){
            $cates = Category::all();
            cache('cates',$cates);
        }
        foreach ($cates as &$item){
            if ($item->cate_name == $cate){
                $item->active = 1;
            }else{
                $item->active = 0;
            }
        }
        $this->assign([
            'cates' => $cates,
            'books' => $books,
            'order' => $order,
            'end' => $end,
        ]);
        if (isMobile()){
            if (!empty($tag)){
                $this->assign('header_title',$tag);
            }else{
                $this->assign('header_title','分类');
            }
        }
        return view($this->tpl);
    }

    public function catelist(){
        $cates = cache('catelist_cates');
        if (!$cates){
            $cates = Category::all();
            foreach ($cates as &$cate) {
                $cate['count'] = Db::query("SELECT COUNT(id) as count FROM " .$this->prefix. "book WHERE category LIKE '%"
                    .$cate->cate_name."%'")[0]['count'];
            }
            cache('catelist_tags',$cates);
        }
        $all_count = cache('book_all_count');
        if (!$all_count){
            $all_count = Db::query('SELECT COUNT(id) as count FROM ' .$this->prefix. 'book ')[0]['count'];
            cache('book_all_count',$all_count);
        }
        $end_count = cache('book_end_count');
        if (!$end_count){
            $end_count = Db::query("SELECT COUNT(id) as count FROM " .$this->prefix. "book WHERE `end` = 1")[0]['count'];
            cache('book_end_count',$end_count);
        }
        $this->assign([
            'cates' => $cates,
            'all_count' => $all_count,
            'end_count' => $end_count,
            'header_title' => '分类',
        ]);
        return view();
    }
}