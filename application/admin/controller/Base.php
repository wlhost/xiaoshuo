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

class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        if (strtolower($this->request->controller())  == 'index'
            && strtolower($this->request->action())  == 'login'){

        }else{
            $this->checkAuth();
        }

    }

    protected function checkAuth(){
        if (!Session::has('admin')) {
            $this->redirect(url('index/login'));
        }
    }
}