<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/12
 * Time: 3:17 PM
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\App;
use think\facade\View;

class Base extends Controller
{
    protected $salt;
    protected function initialize()
    {
        $this->checkAuth();
    }

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->salt = config('site.salt');
        View::share([
            'returnUrl' => $this->request->url(true)
        ]);
    }

    protected function checkAuth(){
        if (!Session::has('admin')) {
            $this->redirect(url('login/index'));
        }
    }
}