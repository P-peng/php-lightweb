<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20 0020
 * Time: 11:12
 */

namespace app\index\controller;


class Email{
    public function index(){
        if(request()->isPost()){
            $data=input('post.');
            //空值校验
            if( empty($data["addresser"]) || empty($data["email"]) || empty($data["content"])){
                //-1 空值
                return json(["type" => "-1"]);
            }
            //长度校验
            if( strlen($data["addresser"])>63 || strlen($data["email"])>63 ){
                //-1 空值
                return json(["type" => "-2"]);
            }
            //邮箱校验
            $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
            if ( !preg_match( $pattern, $data["email"] ) ){
                //-2 邮箱不对
                return json(["type" => "-3"]);
            }
            //存储成功
            date_default_timezone_set("Asia/Shanghai");
            $time = date("Y/m/d H:i:s");
            $data['tim'] = "";
            $data['tim'] =  $time;

            $sign =  db('email')->insert($data);
            if($sign > 0){
                return json(["type" => "1"]);
            }
            //存储异常
            return json(["type" => "false"]);
        }
    }

//    public function cha(Request $request)
//    {
//        if (request()->isPost()) {
//            $data=input('post.');
//
//        }
//
//        return json([
//            "status"=>0,
//            "info"=>"登录失败!",
//            "url" =>"/admin/login/"
//        ]);
//    }
//
//    public function edit()
//    {
//        if(request()->isAjax()) {
//            return json([
//                "type" => "Ajax",
//            ]);
//        }else if(request()->isPost()){
//            return json([
//                "type" => "Post",
//            ]);
//        }else if(request()->isGet()){
//            return json([
//                "type" => "Get",
//            ]);
//        }else{
//            return json(["type" => "false"]);
//        }
//
//    }
}