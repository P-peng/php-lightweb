<?php
namespace app\admin\model;
use think\Model;

class Article extends Model
{
    public function getCata(){//查询所有属于文章,无限级分类
        $cateRes = db('cate')->order('sort desc')->select();

        return $this->sortA($cateRes);//调用
    }

    public function  sortA($data,$pid=0,$level=0){//重构cate model的无限级排序算法
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['pid']==$pid&&$v['type']==2){
                $v['level']=$level;
                $arr[]=$v;
                $this->sortA($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }


}