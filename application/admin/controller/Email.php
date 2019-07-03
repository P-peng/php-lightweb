<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21 0021
 * Time: 11:04
 */

namespace app\admin\controller;


class Email extends Common
{
    public function index(){
        $data = db("email")->order("tim desc")->paginate(10);
        $this->assign(array(
            "data" => $data,
        ));
        return view("lst");
    }


    public function read($id){

//            dump($id);
//            die;
        $data = db("email")->find($id);
        db()->query("update lw_email SET status = '1' WHERE id = $id");
        $this->assign(array(
            "data" => $data,
        ));
        return view("read");


    }

    public function del($id){
//        dump( request()->header()["referer"]);
//        die;
        $sign = db()->query("DELETE FROM lw_email WHERE id = $id");
        if($sign > 0 ){
            $this->redirect(request()->header()["referer"]);
        }else{
            $this->error("错误信息");
        }
    }
}