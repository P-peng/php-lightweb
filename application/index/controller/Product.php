<?php
namespace app\index\controller;
use app\index\model\Product as productModel;

class Product extends Common//用于顶级栏目的子栏目
{
    public function index($id){
        $proObj = new ProductModel();
        $proData = $proObj->getAllProData($id);//模型操作，通过传入id  获取所有产品，分页取9
        $proCate = $proObj->getAllCate($id);

        $this->assign(array(//注册成功后面 没修改完全
            'proData' => $proData,
            'proCate' => $proCate,
        ));
        return view('product');
    }


}
