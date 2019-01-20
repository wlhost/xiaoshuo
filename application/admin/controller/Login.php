<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/1/15
 * Time: 15:30
 */

namespace app\admin\controller;

use app\model\Admin;
use think\Controller;
use think\Request;
class Login extends Controller
{
    public function index(){
        return view();
    }

    public function login(Request $request){
        if ($request->isPost()){
//            $verify_code = $request->param('verify_code');
//            if( !captcha_check($verify_code ))
//            {
//                $this->error('验证码错误','/admin/login/index','',1);
//            }
            $username = $request->param('admin');
            $password = md5($request->param('password').config('site.salt'));
            $map = array();
            $map[] = ['username','=',$username];
            $map[] = ['password', '=', $password];
            $admin = Admin::where($map)->find();
            if (!$admin){
                $this->error('用户名或密码错误','/admin/login/index','',1);
            }
            $admin->last_login_time = time();
            $admin->isUpdate(true)->save();
            session('admin',$admin->username);
            $this->success('登录成功','admin/index/index','',1);
        }else{
            return view('index');
        }
    }

    public function logout(){
        session('admin',null);
        $this->redirect('/admin/login/index');
    }
}