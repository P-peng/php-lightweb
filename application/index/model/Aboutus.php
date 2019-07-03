<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Aboutus extends Model{
    public function getAllData($id){//
        $productesObj = new Productes();
        $data = db('cate')->select();
        $allId = $productesObj->delTreeA($data,$id);//方法调用，获取所有子栏目
        $allId[] = $id;//加上当前id
        $allId = $this->piheid($allId);
        $articleData = db("article")->where("$allId")->select();

        return $this->section($articleData);
    }

    public function piheid( $allId){//数组拼合id
        $str = "";
        for($i=0;$i<count($allId);$i++){
            $str = $str ."cateid = ". $allId[$i] ." or ";
        }
        $str = substr($str,0,strlen($str)-4);//去后面 or
        return $str;
    }

    public function section($data){//将content进行分段操作
        static $arr = array();
//        $arr = array();
//        $start = 0;
//        $end = 1;
//        $a = '';
//        if($a == ''){
//            echo 123;
//        }else{
//            echo -2-2;
//        }die;
        $cishu = count($data);//数组$data的长度
        for($i=0;$i<$cishu;$i++){
            $arr = explode("</p>",$data[$i]['content']);//将</p>去除
            for($j=0;$j<count($arr);$j++){
                $arr[$j] = str_replace('<p>',"",$arr[$j]);//将<p>去除
                $arr[$j] = str_replace('<br/>',"",$arr[$j]);//将<p>去除

            }
            $arr =  array_filter($arr);//过滤数组空值
            $data[$i]['duan'] = $arr;
            $arr = null;
        }
        return $data;

//        for($i=0;$i<$cishu;$i++){
//            dump($data[$i]['content']);
//            $num = substr_count($data[$i]['content'],'<p>');//要截取的次数
//            echo $num."<br/>";
//            for($j=0;$j<$num;$j++){
//                echo "搜索前end=".$end."<br/>";
//                $end = strpos($data[$i]['content'],'<p>',$end);
//                echo $j."次".'start='.$start."<Br/>";
//                echo $j."次".'end='.$start."<Br/>";
//                $arr[] = substr($data[$i]['content'],$start,$end);
//
//                $start = $end;
//                $end--;
//
//            }
//            $data['duan'] = $arr;
//        }

//        dump($arr);
//        dump($data);
//        die;

    }

}