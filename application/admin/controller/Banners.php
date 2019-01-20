<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/18
 * Time: 4:10 PM
 */

namespace app\admin\controller;

use app\model\Banner;
use think\Request;
use think\facade\App;

class Banners extends Base
{
    public function index()
    {
        $banners = Banner::with('book')->paginate(5, true);
        $count = count($banners);
        $this->assign([
            'banners' => $banners,
            'count' => $count
        ]);
        return view();
    }

    public function create()
    {
        return view();
    }

    public function save(Request $request)
    {
        $data = $request->param();

        if (!empty($request->file())) {
            $pic = $request->file('pic_name');
            $dir = App::getRootPath() . '/public/static/upload/banner/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $info = $pic->validate(['size' => 2048000, 'ext' => 'jpg,jpeg,png'])->rule('md5')->move($dir);
            if ($info) {
                $data['pic_name'] = $info->getSaveName();
            }
        }
        $result = Banner::create($data);
        if ($result) {
            $this->success('添加成功', 'index', '', 1);
        } else {
            $this->error('添加失败');
        }

    }

    public function edit($id)
    {
        $returnUrl = input('returnUrl');
        $banner = Banner::get($id);
        $this->assign([
            'banner' => $banner,
            'returnUrl' => $returnUrl
        ]);
        return view();
    }

    public function update(Request $request)
    {
        $data = $request->param();
        $returnUrl = $data['returnUrl'];
        if (!empty($request->file())) {
            $pic = $request->file('pic_name');
            $dir = App::getRootPath() . '/public/static/upload/banner/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $info = $pic->validate(['size' => 2048000, 'ext' => 'jpg,jpeg,png'])->rule('md5')->move($dir);
            if ($info) {
                $data['pic_name'] = $info->getSaveName();
            }
        }
        $result = Banner::update($data);
        if ($result) {
            $this->success('编辑成功', $returnUrl, '', 1);
        } else {
            $this->error('修改失败');
        }
    }

    public function delete($id)
    {
        $result = Banner::destroy($id);
        if ($result) {
            return ['err' => 0, 'msg' => '删除成功'];
        } else {
            return ['err' => 1, 'msg' => '删除失败'];
        }
    }
}