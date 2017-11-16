<?php
namespace app\back\controller;
use think\Controller;
use think\Session;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Image;
use think\Db;
use think\Request;
class Login extends Controller {
    public function login(){
        header("Content-type:text/html;charset=utf-8;");
                if(isset($_GET['exit'])){
                    Session::delete('admin');
                }
		if(Request::instance()->isPost()){
			$user       = input('post.username');
			$password   = input('post.pwd');
			$password = md5($password);
			$status=Db::name('admin')->where("username='$user'")->find(); 
                        if($status['pwd']==$password){
                                Session::set('admin',array("admin_id"=>$status['admin_id']));
				$this->success("登录成功！","/back/index");
			}else{
				$this->error("登录失败！");
			}
		}else{
			if(Session::has('admin')){
                            header("Location: ".url('/back/index'));
                            exit();
			}
			return $this->fetch();
		}
    }
}