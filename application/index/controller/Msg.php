<?php
namespace app\index\controller;
use app\index\model\Msg as MsgModel;
use think\Db;
class Msg extends Common
{
    public function index($id)
    {
        //根据id取产品信息
        $proData = db('product')->where('id',$id)->find();
//        dump($proData);
        //根据cateid取栏目
        $msgObj = new MsgModel();
        $cateData = $msgObj->getChildren($proData['cateid']);//模型操作，取当前产品分类的，所有兄弟和他的父亲,此方法只一级操作，没有二级
        $order =  Db::query("select * from lw_product order by rand() limit 3");
        $this->assign(array(
            'proData' => $proData,
            'cateData' => $cateData,
            'order' => $order,
        ));
        return view('test6');
    }

    public function test($id)
    {
        //根据id取产品信息
        $proData = db('product')->where('id',$id)->find();
//        dump($proData);
        //根据cateid取栏目
        $msgObj = new MsgModel();
        $cateData = $msgObj->getChildren($proData['cateid']);//模型操作，取当前产品分类的，所有兄弟和他的父亲,此方法只一级操作，没有二级
        $order =  Db::query("select * from lw_product order by rand() limit 3");
        $this->assign(array(
            'proData' => $proData,
            'cateData' => $cateData,
            'order' => $order,
        ));
        return view('test6');
    }

}
