<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Admin extends Model {
    public function addAdmin($data){//管理注册模型方法
        if(empty($data) || !isset($data)){
            return false;
        }
        if($data['pw']) {
            $data['pw'] = md5($data['pw']);
        }
        $adminArray = array();
        $adminArray['name'] = $data['name'];
        $adminArray['pw'] = $data['pw'];
        if($this->save($adminArray)){
            $group = array();
            $group['uid'] = $this->id;
            $group['group_id'] = $data['group_id'];
            db('auth_group_access')->insert($group);
            return true;
        }else{
            return false;
        }
    }

    public function getAllData()//获取所有管理数据
    {
        $data = Db::table('lw_admin')->field(['id','name'])->select();//选择字段查询
        return $data;
    }

    public function editPw($data){//根据id修改密码
        $data['pw'] = md5($data['pw']);
        $sign = Db::name('admin')->where('id', $data['id'])->update(['pw' => $data['pw']]);
        return $sign;

    }

    public function getOneData($id){//根据id查询单个id数据
        $data = Db::table('lw_admin')->where('id',$id)->find();
        $data['pw'] = null;
        return $data;
    }

    public function login($data){
        $admin = Admin::getByname($data['name']);
//         sql注入测试
//        echo $this->getLastSql();die;
//        dump($data);
//        dump($admin);die;
        if($admin){
            if(md5($data['pw']) == $admin['pw']){
                session('id__DengZhiPeng', $admin['id']);
                session('name__DengZhiPeng', $admin['name']);
                return 1;//成功登录
            }else{
                return 0;//密码错误登录
            }
        }else{
            return -1;//用户不存在
        }
    }
}