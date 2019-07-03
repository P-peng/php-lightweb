<?php
namespace app\index\model;
use think\Model;
use app\admin\model\Cate as cateModel;
class Productes extends Model{

    public function getAllProData($id){//通过传入id  获取所有产品

        $data = db('cate')->select();
        $cateAllId = $this->delTreeA($data,$id);//
        $cateAllId[] = $id;//加入当前id

        $data = $this->getAllData($cateAllId);
        return $data;
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

    public function getAllData($cateAllId){
        $str = "";
        for($i=0;$i<count($cateAllId);$i++){//拼合条件where
            $str = $str . "cateid=" . "$cateAllId[$i]"." or ";
        }
        $str = substr($str,0,strlen($str)-4);//去后面 or
        $proData = db('product')->where(" $str ")->paginate('12');//分页操作
        return $proData;
    }

    public function getAllCate($id){//根据传入id，找出所有兄弟，无无限极分类
        return  db('cate')->where('pid',$id)->field('id,cateName')->select();
    }
}