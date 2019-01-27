<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/12
 * Time: 3:17 PM
 */
namespace app\admin\controller;

use think\facade\App;

class Index extends Base
{
    public function index(){
        $site_name = config('site.site_name');
        $url = config('site.url');
        $salt = config('site.salt');
        $xzh = config('site.xzh');
        $this->assign([
            'site_name' => $site_name,
            'url' => $url,
            'salt' => $salt,
            'xzh' => $xzh
            ]);
        return view();
    }

    public function update(){
        $site_name = input('site_name');
        $url = input('url');
        $salt = input('salt');
        $xzh = input('xzh');
        $code = <<<INFO
<?php
return [
    'url' => '{$url}',
    'site_name' => '{$site_name}',
    'xiongzhang' => '{$xzh}',
    'salt' => '{$salt}'
];
INFO;
        file_put_contents(App::getRootPath() . 'config/site.php', $code);
        $this->success('修改成功','index','',1);
    }

    public function clearcache(){
        clearcache();
        $this->success('缓存清理成功','/admin','',1);
    }
}