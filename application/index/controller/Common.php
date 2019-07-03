<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    public function _initialize(){
        //导航条,栏目条
        $this->getNavCate();
        //系统数据
        $this->getSysData();
    }
    public function getSysData(){
        $sys = db('sys')->where('id','1')->find();

        $this->assign('sys',$sys);
    }

    public function getNavCate(){
        $cate = db('cate')->where('pid',0)->order("sort desc")->select();
        foreach($cate as $k => $v){
            $children = db('cate')->where('pid',$v['id'])->select();
            if($children){
                $cate[$k]['children'] = $children;
            }else{
                $cate[$k]['children'] = -1;
            }
        }
        $this->assign('navCate',$cate);
    }

    public function K(){//从定向  到products页面
        $this->redirect('products/index',array('id'=>3));
    }

    public function search(){
        $keys = input('get.');
        $data = db('product')->where('keys',$keys['keys'])->find();
        //不存在
        if(empty($data)){
            echo "<script type='text/javascript'>
                    alert(\"Not found relevant product information!\");
                    window.location.href = \"K\";
                    </script>";
        }else {//当关键词只有一个时，转到详情页面

            $this->redirect('msg/index',array('id'=>$data['id']));
        }
        //当关键词多个时，转到列表页面，后期再加





    }
}
