<?php
namespace app\admin\model;
use think\Model;
class Product extends Model {
    public function getAllData($id){
        $cataObjM = new Cate();//使用cate模型
        $cateData = db('cate')->select();
        $allId = $cataObjM->delTreeA($cateData,$id);//使用cate模型  查找算法
        $allId[] = $id;
        return $this->getDataW($allId,$id);
    }

    public function getDataW($cateAllId,$id){//此方法  利用传入的id数组，拼合后，查询产品数据，返回产品数组   W无意义
        $str = "";
        for($i=0;$i<count($cateAllId);$i++){//拼合条件where
            $str = $str . "cateid=" . "$cateAllId[$i]"." or ";
        }
        $str = substr($str,0,strlen($str)-4);//去后面 or

        $proData = db('product')->where(" $str ")->paginate(10,false,['query' => ['id'=>$id]])->each(function ($item, $key){
            $item['cateName'] = db('cate')->where('id',$item['cateid'])->field('cateName')->find()['cateName'];
            return $item;
        });//分页操作  + 分页式字段修改,
        return $proData;
    }

    public function getDataSearch($keys){
//        $data = db('product')->alias('p')->join('cate c','p.cateid = c.id')->where('p.keys',$keys)->field('p.id,p.pdName,p.keys,p.desc,p.sort,p.thumb1,p.thumb2,p.thumb3,p.thumb4,p.content,p.cateid,c.cateName');

//            ->each(function ($item, $key){
//            $item['cateName'] = db('cate')->where('id',$item['cateid'])->field('cateName')->find()['cateName'];
//            return $item;
//        });
        $data = db('product')->where("keys",$keys)->paginate(10)->each(function ($item, $key){
            $item['cateName'] = db('cate')->where('id',$item['cateid'])->field('cateName')->find()['cateName'];
            return $item;
        });//分页操作  + 分页式字段修改,
        return $data;
    }

    public function getCata(){//查询所有属于图片,无限级分类
        $cateRes = db('cate')->order('sort desc')->select();

        return $this->sortA($cateRes);//调用
    }

    public function  sortA($data,$pid=0,$level=0){//重构cate model的无限级排序算法
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['pid']==$pid&&$v['type']==1){
                $v['level']=$level;
                $arr[]=$v;
                $this->sortA($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }
}