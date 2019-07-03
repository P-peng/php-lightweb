<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18 0018
 * Time: 9:32
 */

namespace app\admin\model;


class Sys{



    public function tree(){//只查找 cate表id=3/4 和及其所有子栏目  支持无限级别
        $data = db("cate")->select();

        $arr = $this->sortById($data,3,1);
        array_unshift($arr,$this->getCateById($data,3));

        $arr2 = $this->sortById2($data,4,1);
        array_unshift($arr2,$this->getCateById($data,4));
        foreach ($arr2 as $k => $v){
            $arr[] = $v;
        }
        return $arr;
    }

    private function  sortById($data,$pid=0,$level){//排序树算法，A表示算法,用于无限级分类
        static $arr = array();
        foreach ($data as $k => $v) {
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sortById($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    private function  sortById2($data,$pid=0,$level){//排序树算法，A表示算法,用于无限级分类
        static $arr = array();
        foreach ($data as $k => $v) {
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sortById($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    private function getCateById($data,$id){//
        foreach ($data as $k => $v){
            if($v['id'] == $id){
                $v['level'] = 0;
                return $v;
            }
        }
    }
}