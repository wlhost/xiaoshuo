<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/12
 * Time: 3:17 PM
 */
namespace app\admin\controller;

use think\facade\Config;
use think\Request;

class Index extends Base
{
    public function index(){
        $site_name = config('site.site_name');
        $url = config('site.url');
        $admin = config('site.admin');
        $this->assign([
            'site_name' => $site_name,
            'url' => $url,
            'admin' => $admin
        ]);
        return view();
    }

    public function clearcache(){
        clearcache();
        $this->success('缓存清理成功','/admin','',1);
    }
}