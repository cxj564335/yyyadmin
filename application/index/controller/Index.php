<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Image;
use think\Db;
use think\Request;
class Index extends Controller 
{
    public function index()
    {
        echo '你好羊羊羊！！！';
        return $this->fetch();
    }
}
