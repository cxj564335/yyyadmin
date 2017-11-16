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
class User extends Base {
    public function index(){
        return $this->fetch();
    }
    public function userlist(){
        $list=Db::name('member')
                ->field('m.*,ml.name levelname')
                ->alias('m')
                ->join('__MEMBER_LEVEL__ ml','m.level = ml.id','LEFT')
                ->order('m.id', 'desc')
                ->paginate(20);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function useradd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $mobile = input('post.mobile');
                $isuser=Db::name('member')->where("mobile=$mobile")->find();
                if(empty($isuser)){
                    $data = input('post.');
                    $data['password'] = md5($data['password']);
                    $data['reg_time'] = time();
                    db('member')->insert($data);
                    echo '<script>alert("添加成功");</script>';
                } else {
                    echo '<script>alert("手机号重复添加失败");</script>';
                }
            }else{
                $data = input('post.');
                if(!empty($data['password'])){
                    $data['password'] = md5($data['password']);
                } else {
                    unset($data['password']);
                }
                db('member')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            if(!empty($id)){
                $myuser = Db::name('member')->where("id=$id")->find();
                $this->assign('myuser', $myuser);
            }
        }
        $mymember_level = Db::name('member_level')->where("id>0")->select();
        $this->assign('mymember_level', $mymember_level);
        return $this->fetch();
    }
    public function userlevel(){
        $userlevellist=Db::name('member_level')->where("id>0")->order('id', 'desc')->select();
        $this->assign('userlevellist', $userlevellist);
        return $this->fetch();
    }
    public function userleveladd(){
        if(Request::instance()->isPost()){
            $id= input('post.id');
            if(empty($id)){
                $name = input('post.name');
                $isuser=Db::name('member_level')->where(array('name'=>$name))->find();
                if(empty($isuser)){
                    $data = input('post.');
                    db('member_level')->insert($data);
                    echo '<script>alert("添加成功");</script>';
                } else {
                    echo '<script>alert("等级已存在添加失败");</script>';
                }
            }else{
                $data = input('post.');
                db('member_level')->where('id', $id)->update($data);
                echo '<script>alert("修改成功");</script>';
            }
        }
        if(Request::instance()->isGet()){
            $id= input('get.id');
            if(!empty($id) && $id>0){
                $mymember_level = Db::name('member_level')->where("id=$id")->find();
                $this->assign('mymember_level', $mymember_level);
            }
        }
        return $this->fetch();
    }
    public function main(){
        return $this->fetch();
    }
}