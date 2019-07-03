<?php
namespace app\index\controller;
use think\Db;
class Blog extends Common
{
    public function index(){
        $data = db('blog')->select();
        //左侧栏目随机取样10个
        $leftData = Db::query("select * from lw_product order by rand() limit 10");

        $this->assign(array(
           'blogData' => $data,
            'leftData' => $leftData,
        ));
        return view('blog');
    }
}
