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
class Article extends Base {
    public function index(){
        return $this->fetch();
    }
    public function articlelist(){
        $list=Db::name('article')->where("id>0")->order('id', 'desc')->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function articleadd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $data = input('post.');
                $data['createtime'] = time();
                db('article')->insert($data);
                echo '<script>alert("添加成功");</script>';
            }else{
                $data = input('post.');
                db('article')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            if(!empty($id)){
                $myarticle = Db::name('article')->where("id=$id")->find();
                $this->assign('myarticle', $myarticle);
            }
        }
        return $this->fetch();
    }
    public function commentlist(){
        $list=Db::name('comment')->where("id>0")->order('id', 'desc')->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function commentadd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $data = input('post.');
                $data['time'] = time();
                db('comment')->insert($data);
                echo '<script>alert("添加成功");</script>';
            }else{
                $data = input('post.');
                db('comment')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            $aid= input('get.aid');
            if(!empty($id)){
                $mycomment = Db::name('comment')->where("id=$id")->find();
                $this->assign('mycomment', $mycomment);
            }
            if(!empty($aid)){
                $this->assign('aid', $aid);
            }
        }
        return $this->fetch();
    }
}