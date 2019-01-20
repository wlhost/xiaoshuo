<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/1/15
 * Time: 14:53
 */

namespace app\admin\controller;


class Admin extends Base
{
    protected $adminService;
    protected function initialize()
    {
        $this->adminService = new AdminService();
    }

    public function index(){
        $data = $this->adminService->GetAll();
        $this->assign([
            'admins' => $data['admins'],
            'count' => $data['count']
        ]);
        return view();
    }
}