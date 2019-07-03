<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Msg extends Model{
    public function getChildren($childrenId){
        static $arr = array();
        $cateData = db('cate')->where('id',$childrenId)->field('pid')->find();//顶级栏目id
        $arr[] = db('cate')->where('id',$cateData['pid'])->field('id,cateName')->find();//顶级栏目信息
        if(empty($arr[0])){
            $arr[0]['id'] = -1;
            $arr[0]['cateName'] = -1;
        }


        $catePid = db('cate')->where('pid',$cateData['pid'])->field('id')->select();//只找一级,为上一级
        static $arr2 = array();
        foreach ($catePid as $k => $v){//子类的所有id
            $arr2[] = Db('cate')->where('id',$v['id'])->field('id,cateName')->find();
        }
        if(!empty($arr2)){
            $arr[0]['children'] = $arr2;
        }else{
            $arr[0]['children'] = -1;
        }

        return $arr;
    }


}