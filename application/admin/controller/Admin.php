<?php
namespace app\admin\controller;

use app\admin\controller\Common;
use think\Db;
use app\admin\model\Admin as AdminModel;
class Admin extends Common
{
    public function lst(){//管理员列表
        $adminObj = new AdminModel();
        $data = $adminObj->getAllData();

        $this->assign('data',$data);
        return view();
    }

    public function add(){//管理员添加
        if(request()->isPost()){
            $data = input('post.');
            if($data['pw'] != $data['pw2']){
                $this->error('两次密码输入不一致');
            }
            $adminObj = new AdminModel();//模型层业务逻辑
            if($adminObj->addAdmin($data)){
                $this->success('保存成功，系统已经录入。',url('lst'));
            }else{
                $this->error('保存失败，系统录入失败。');
            }
            unset($data);
            unset($adminObj);
        }
        $authGroupRes=db('auth_group')->select();
        $this->assign('authGroupRes',$authGroupRes);
        return view();
    }

    public function edit($id){//管理员编辑
        $adminObj = new AdminModel();
        if(request()->isPost()){
            $data = input('post.');
            if($data['pw'] != $data['pw2']){
                $this->error('两次密码不一致');
            }
            if($adminObj->editPw($data) != 0){
                $this->success('修改成功',url('admin/lst'));
            }else{
                $this->error('修改密码失败，原因可能与原密码相同');
            }
            dump($data);die;
        }
        $this->assign('data',$adminObj->getOneData($id));
        return view();
    }

    public function del($id){
        if(Db::table('lw_admin')->delete($id) !=0){
            $this->success('删除成功',url('admin/lst'));
        }else{
            $this->error('删除失败');
        }
    }

    public function logout(){
        session(null);
        $this->success("退出登录成功",url('Login/index'));
    }
}
