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
class Goods extends Base {
    public function index(){
        return $this->fetch();
    }
    public function classifylist(){
        $classifylist=Db::name('classify')->where("id>0")->order('id', 'desc')->select();
        $this->assign('classifylist', $classifylist);
        return $this->fetch();
    }
    public function classifyadd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $name = input('post.name');
                $isuser=Db::name('classify')->where(array('name'=>$name))->find();
                if(empty($isuser)){
                    $data = input('post.');
                    db('classify')->insert($data);
                    echo '<script>alert("添加成功");</script>';
                } else {
                    echo '<script>alert("分类已存在添加失败");</script>';
                }
            }else{
                $data = input('post.');
                db('classify')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            if(!empty($id)){
                $myclassify = Db::name('classify')->where("id=$id")->find();
                $this->assign('myclassify', $myclassify);
            }
        }
        return $this->fetch();
    }
    public function goodslist(){
        $list=Db::name('goods')->where("id>0")->order('id', 'desc')->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function goodsadd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $data = input('post.');
                db('goods')->insert($data);
                echo '<script>alert("添加成功");</script>';
            }else{
                $data = input('post.');
                db('goods')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            if(!empty($id)){
                $mygoods = Db::name('goods')->where("id=$id")->find();
                $this->assign('mygoods', $mygoods);
            }
        }
        return $this->fetch();
    }
}