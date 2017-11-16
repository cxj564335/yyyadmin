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
class Order extends Base {
    public function index(){
        return $this->fetch();
    }
    public function Orderlist(){
        if(Request::instance()->isGet()){
            $status= input('get.status');
            if($status==0 && isset($_GET['status'])){
                $where['status'] = '0';
            }elseif($status==1){
                $where['status'] = '1';
            }elseif($status==2){
                $where['status'] = '2';
            }elseif($status==3){
                $where['status'] = '3';
            }elseif($status==4){
                $where['status'] = '4';
            }else{
               $where['status'] = ['>=',0];
            }
            $list=Db::name('order')->where($where)->order('id', 'desc')->paginate(20);
        }else{
            $list=Db::name('order')->where('id>0')->order('id', 'desc')->paginate(20);
        }
        
        $this->assign('list', $list);
        return $this->fetch();
    }


}