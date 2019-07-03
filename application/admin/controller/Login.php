<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as adminModel;
class Login extends Controller
{
    public function index()
    {
        if(request()->isPost()){//登录验证
            $data = input('post.');
            $dataObj = new adminModel();
            $state = $dataObj->login($data);//模型方法
            if($state == -1){
                echo "<script>alert('用户不存在');</script>";
            }else if($state == 0){
                echo "<script>alert('密码错误');</script>";
            }else{
                $this->redirect('index/index');
            }
        }
        return view('login');
    }
}
