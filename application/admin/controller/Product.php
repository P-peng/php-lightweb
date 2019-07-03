<?php
namespace app\admin\controller;

use app\admin\model\Cate as CateModel;
use app\admin\model\Product as ProductModel;
class Product extends Common
{
    public function index(){
        //联合查询 双表
        $data = db('product')->alias('p')->join('cate c','p.cateid = c.id')->field('p.*,c.cateName')->paginate('10');
//        foreach ($data as $k => $v){
//            if($data[$k]['thumb1'])
//               $data[$k]['thumb1'] = "http://localhost/lightweb/".$data[$k]['thumb1'];
//            if($data[$k]['thumb2'])
//                $data[$k]['thumb2'] = "http://localhost/lightweb/".$data[$k]['thumb2'];
//            if($data[$k]['thumb3'])
//                $data[$k]['thumb3'] = "http://localhost/lightweb/".$data[$k]['thumb3'];
//            if($data[$k]['thumb4'])
//                $data[$k]['thumb4'] = "http://localhost/lightweb/".$data[$k]['thumb4'];
//            dump($v);
//        }die;
//        dump($data);die;
//        $data = db('product')->paginate(10);
//        $data2 = array();
//        for ($i=0;$i<count($data);$i++){
//
//            dump($data[$i]);
//        }
//        foreach ($data as $k => $v){
//
//            $data2[] = $v;
//            if($data2[$k]['thumb1'])
//               $data2[$k]['thumb1'] = "http://localhost/lightweb/".$data2[$k]['thumb1'];
//            if($data2[$k]['thumb2'])
//                $data2[$k]['thumb2'] = "http://localhost/lightweb/".$data2[$k]['thumb2'];
//            if($data2[$k]['thumb3'])
//                $data2[$k]['thumb3'] = "http://localhost/lightweb/".$data2[$k]['thumb3'];
//            if($data2[$k]['thumb4'])
//                $data2[$k]['thumb4'] = "http://localhost/lightweb/".$data2[$k]['thumb4'];
//            $data2[$k]['type'] = implode('',db('cate')->where('id',$data2[$k]['cateid'])->field('cateName')->find());
//
//
//        }dump($data2);die;
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();//模型调用



        $this->assign(array(
            'data'=>$data,
            'cateRes' =>  $cateRes,
            ));
        return view('lst');
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $files = request()->file('thumb');
            $i = 1;
            foreach($files as $file){
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->rule('date')->move('../public/uploads');
                if($info){
                    $date = date('Ymd');
                    $name =  "public/uploads/".$date."/".$info->getFilename();//可改善,图片地址
                    $sign = "thumb$i";
                    $data[$sign] = $name;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError()."<br/>";
                }
                $i++;
            }
            if(db('product')->insert($data)){
                echo "<script>alert('添加成功');</script>";
            }else{
                echo "<script>alert('添加失败');</script>";
            }
        }

        $proObj = new ProductModel();
        $cateRes = $proObj->getCata();//模型调用+

//        $cateObj = new CateModel();
//        $cateRes = $cateObj->cateTree();
        $this->assign('cateRes',$cateRes);
        unset($cateRes);
        unset($cateObj);
        return view('add');
    }

    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $files = array();
            for($i=2;$i<5;$i++){
                if(empty($data["wu$i"]))//存在
                    continue;
                if($data["wu$i"] == 'on'){//存在确认为空
                    $data["thumb$i"] = NULL;
                    unset($data["wu$i"]);
                    //查询数据，并删除图片
                    $delF = db('product')->where('id',$data['id'])->field("thumb$i")->find();
                    if(file_exists(ROOT_PATH.$delF["thumb$i"])&&$delF["thumb$i"]){//如果exist,则delete
                        unlink(ROOT_PATH.$delF["thumb$i"]);//unlink 可删除图片   可删除文件
                    }
                }
            }
            for($i=1;$i<5;$i++){
                $files["thumb$i"] = request()->file("thumb$i");
            }
            for($i=1;$i<5;$i++){
                if(!empty($files["thumb$i"])){//有传文件过来，操作
                    $info = $files["thumb$i"]->rule('date')->move('../public/uploads');//移动文件到服务器
                    if($info){
                        $date = date('Ymd');
                        $name =  "public/uploads/".$date."/".$info->getFilename();//可改善,图片地址
                        $sign = "thumb$i";
                        $data[$sign] = $name;

                        //成功移动后删除原图片
                        $delF = db('product')->where('id',$data['id'])->field("thumb$i")->find();//查地址
                        if(file_exists(ROOT_PATH.$delF["thumb$i"])&&$delF["thumb$i"]){//如果exist,则delete
                            unlink(ROOT_PATH.$delF["thumb$i"]);//unlink 可删除图片   可删除文件
                        }
                    }
                }else{//没传文件过来不操作
                }
            }
            $value = db('product')->where('id',$data['id'])->update($data);
//            $files1 = request()->file("thumb1");
//            $files2 = request()->file("thumb2");
//            $files3 = request()->file("thumb3");
//            $files4 = request()->file("thumb3");
//            dump($files1);dump($files4);
            if($value>0){
                $this->redirect('index');
            }else{
                $this->error('修改失败');
            }
        }
        $oneData = db('product')->where('id',$id)->find();//产品资料

        $proObj = new ProductModel();
        $cateRes = $proObj->getCata();//模型调用+


        $this->assign(array(
            'oneData' => $oneData,
            'cateRes' => $cateRes
        ));
        return view('edit');
    }

    public function del($id){
        $data = db('product')->where('id',$id)->field('thumb1,thumb2,thumb3,thumb4')->find();
        $data = array_filter($data);
        $i = 1;
        foreach ($data as $k => $v){
            if(file_exists(ROOT_PATH.$data["thumb$i"])){//如果exist,则delete
                unlink(ROOT_PATH.$data["thumb$i"]);//unlink 可删除图片   可删除文件
            }
            $i++;
        }
        if(db('product')->delete($id)>0){
            $this->redirect('index');
        }else{
            $this->error('删除产品失败');
        }
    }

    public function classify($id){//
        //判断接收的id  是否属于图片类型，不是直接打回
        $type = db('cate')->where('id',$id)->field('type')->find();
        if($type['type'] != 1){//图片类型属于1
            $this->error('错误信息,原因可能是,此栏目不属于图片类型');
        }
        $productObjM = new ProductModel();//模型操作

        $data = $productObjM->getAllData($id);//获取所有属于此栏目及所属子栏目产品数据+分页
//        $proData = db('product')->where(" $data  ")->paginate(10,false,['query' => ['id'=>$id]]);
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();//模型调用
        $this->assign(array(
            'id' => $id,
            'productData' => $data,//
            'cateRes' =>  $cateRes,
        ));
        return view();
    }

    public function search(){
        if(request()->get()) {
            $data = input('get.');
            if(empty($data)||isset($data)){
                $this->assign('错误信息，原因可能关键字为空');
            }
            $productObjM = new ProductModel();//模型操作
            $data = $productObjM->getDataSearch($data['keys']);
            if(empty($data[0])){//无数据直接赋值-1
                $data = null;
                $data = -1;
            }
            $cateObj = new CateModel();
            $cateRes = $cateObj->cateTree();//模型调用
            $this->assign(array(
                'data' => $data,
                'cateRes' =>  $cateRes,
            ));
            return view();
        }
    }
}
