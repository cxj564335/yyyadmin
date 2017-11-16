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
class Set extends Base {
    public function index(){
        return $this->fetch();
    }
    public function setting(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $data = input('post.');
                db('setting')->insert($data);
                echo '<script>alert("添加成功");</script>';
            }else{
                $data = input('post.');
                db('setting')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        $setting = Db::name('setting')->order('id', 'desc')->find();
        $this->assign('setting', $setting);
        return $this->fetch();
    }
    public function wxset(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $data = input('post.');
                db('wx_set')->insert($data);
                echo '<script>alert("添加成功");</script>';
            }else{
                $data = input('post.');
                db('wx_set')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        $wxset = Db::name('wx_set')->order('id', 'desc')->find();
        $this->assign('wxset', $wxset);
        return $this->fetch();
    }


}