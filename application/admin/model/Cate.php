<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Cate extends Model {
    public function addCate($data){//增加栏目
        if($this->save($data)>0){
            return true;
        }else{
            return false;
        }
    }

    public function cateTree(){ //排序树
        $cateRes = db('cate')->order('sort desc')->select();
        return $this->sortA($cateRes);//调用
    }

    public function  sortA($data,$pid=0,$level=0){//排序树算法，A表示算法,用于无限级分类
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sortA($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

    public function delTree($id){//删除树，删除无限级分类的子栏目
        $data = db('cate')->select();
        $arr = $this->delTreeA($data,$id);//调用
        $id = intval($id);
        $arr[] = $id;
        $str = implode(",", $arr);
        if(Db::table('lw_cate')->delete($str)>0){
            return true;
        }else{
            return false;
        }
    }

    public function delTreeA($data,$id){//查找所有栏目的子栏目的id,支持无限级
        static $arr = array();
        foreach ($data as $k => $v){
            if($v['pid'] == $id){
                $arr[] = $v['id'];
                $this->delTreeA($data,$v['id']);
            }
        }
        return $arr;
    }

    public function upChilrenCateType($id,$type){

        $cateRes = db('cate')->order('sort desc')->select();

        $allId = $this->delTreeA($cateRes,$id);//调用
        for($i=0;$i<count($allId);$i++){
            db('cate')->where('id',$allId[$i])->update(array('type'=>$type));
        }
    }
}