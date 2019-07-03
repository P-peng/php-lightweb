<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

class Common extends Controller
{
    public function _initialize()
    {
        if(!session('id__DengZhiPeng') || !session('name__DengZhiPeng')){//登录session类，
            $this->error('未登录',url('login/index'));
        }
        //权限
        $auth=new Auth();
        $request=Request::instance();
        $con=$request->controller();
        $action=$request->action();
        $name=$con.'/'.$action;
        $notCheck=array('Index/index','Admin/lst','Admin/logout');
        if(session('id__DengZhiPeng')!=1){ //锁定id1是超级管理员
            if(!in_array($name, $notCheck)){
                if(!$auth->check($name,session('id__DengZhiPeng'))){
                    $this->error('没有权限',url('index/index'));
                }
            }
        }
    }
}
