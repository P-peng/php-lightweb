<?php
namespace app\index\model;
use think\Model;
use app\index\model\Productes as productesModel;
class Product extends Model{

    public function getAllProData($id){//通过传入栏目id  获取所有产品
        $pros = new productesModel();
        $data = db('cate')->select();
        $cateAllId =  $pros->delTreeA($data,$id);//使用admin模块的cate类方法
        $cateAllId[] = $id;

        $data = $this->getAllData($cateAllId);
        return $data;
    }

    public function getAllData($cateAllId){
        $str = "";
        for($i=0;$i<count($cateAllId);$i++){//拼合条件where
            $str = $str . "cateid=" . "$cateAllId[$i]"." or ";
        }
        $str = substr($str,0,strlen($str)-4);//去后面 or
        $proData = db('product')->where(" $str ")->paginate('9');//分页操作
        return $proData;
    }

    public function getAllCate($id){//根据传入id，找出所有兄弟，无无限极分类,只找一级
        $pid = db('cate')->where('id',$id)->field('pid')->find();
        $allId = db('cate')->where('pid',$pid['pid'])->field('id,cateName')->select();

        return  $allId;

    }
}