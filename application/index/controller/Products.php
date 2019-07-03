<?php
namespace app\index\controller;
use app\index\model\Productes as productesModel;

class Products extends Common//用于顶级栏目
{
    public function index($id){
        $prosObj = new ProductesModel();
        $prosData = $prosObj->getAllProData($id);//模型操作，通过传入id  获取所有产品，分页取9
        $prosCate = $prosObj->getAllCate($id);//
//        $proIdChildren = \db('product')->where('id',0);
//        $proData = db('product')->where("cateid=9 or cateid=4")->paginate('20');//取product栏目  然后分页操作,
//        $proData = db()->query('select *from lw_product')->paginate('20');
//        $proData = Db::query('select *from lw_product');

//        dump($proCate);die;
        $this->assign(array(//注册成功后面 没修改完全
            'prosData' => $prosData,
            'prosCate' => $prosCate,
        ));
        return view('products');
    }


}
