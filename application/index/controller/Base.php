<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/12/7
 * Time: 10:06 PM
 */

namespace app\index\controller;

use think\App;
use think\Controller;
use think\facade\View;

class Base extends Controller
{
    protected $tpl;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->prefix = config('database.prefix');
        if (isMobile()){
            $this->tpl = 'm'.$this->request->action();
        }else{
            $this->tpl = $this->request->action();
        }
        View::share([
            'url' => config('site.url'),
            'site_name' => config('site.site_name')
        ]);
    }
}