<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/1/19
 * Time: 12:24
 */

namespace app\admin\controller;

use app\model\Category;
use think\Request;

class Categorys extends Base
{
    public function index(){
        $categroys = Category::all();
        $this->assign([
            'categroys' => $categroys,
            'count' => count($categroys)
        ]);
        return view();
    }

    public function create(){
        return view();
    }

    public function save(Request $request){
        $data = $request->param();
        $category = Category::where('cate_name','=',trim($data['cate_name']))->find();
        if ($category){
            $this->error('存在同名分类');
        }
        $category = new Category();
        $category->save($data);
        $this->success('新增分类成功','index','',1);
    }

    public function edit(){
        $returnUrl = input('returnUrl');
        $category = Category::get(input('id'));
        $this->assign([
            'category' => $category,
            'returnUrl' => $returnUrl
        ]);
        return view();
    }

    public function update(Request $request){
        $data = $request->param();
        $category = new Category();
        $category->isUpdate(true)->save($data);
        $this->success('编辑成功',$data['returnUrl'],'',1);
    }
}