<?php

namespace app\back\controller;
use think\Controller;
use think\Db;
use think\Session;

class Base extends Controller {
    function _initialize() {
       if(!(Session::has('admin'))){
            header("Location: ".url('back/login/login'));
            exit();
        }
    }
}