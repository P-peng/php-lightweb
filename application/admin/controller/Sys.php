<?php
namespace app\admin\controller;

use app\admin\model\Cate as CateModel;
use app\admin\model\Sys as SysModel;
class Sys extends Common{
    public function index(){
        $sysData = db('sys')->select()[0];

        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();

//        $SysObj = new SysModel();
//        $SysObj->tree();
//        die;
//
//        dump($cateRes);
//        dump($sysData);die;

        $this->assign(array(
            'cateRes'=> $cateRes,
            'sysData' => $sysData,
        ));
        unset($cateObj);
        return view('lst');
    }
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');

//            dump($data);die;
            //logo1/2图片处理
            for($i=1;$i<3;$i++){
                $logoFile = request()->file("logo$i");
                if($logoFile){//有图片过来才处理
                    $logoFileInfo = $logoFile->rule('date')->move('../public/uploads');//移动文件
                    if($logoFileInfo){//移动成功
                        //将图片信息加入$data
                        $date = date('Ymd');
                        $data["logo$i"] =  "public/uploads/".$date."/".$logoFileInfo->getFilename();//图片地址
                        //删除原图片
                        $delF = db('sys')->where('id',1)->field("logo$i")->find();
                        if(file_exists(ROOT_PATH.$delF["logo$i"])&&$delF["logo$i"]){//如果exist,则delete
                            unlink(ROOT_PATH.$delF["logo$i"]);//unlink 可删除图片   可删除文件
                        }
                    }else{
                        // 上传失败获取错误信息
                        echo $logoFile->getError();
                    }
                }
            }//end logo图片处理

            //start banner1/2/3轮播图片处理
            for($i=1;$i<4;$i++){
                $files["banner$i"] = request()->file("banner$i");//接受文件对象
                if(!empty($files["banner$i"])){//  有文件传过来时
                    $info = $files["banner$i"]->rule('date')->move('../public/uploads');//移动文件
                    if($info){//移动成功
                        //将图片信息加入$data
                        $date = date('Ymd');
                        $data["banner$i"] =  "public/uploads/".$date."/".$info->getFilename();//图片地址
                        //删除原图片
                        $delF = db('sys')->where('id',1)->field("banner$i")->find();
                        if(file_exists(ROOT_PATH.$delF["banner$i"])&&$delF["banner$i"]){//如果exist,则delete
                            unlink(ROOT_PATH.$delF["banner$i"]);//unlink 可删除图片   可删除文件
                        }
                    }else{
                        // 上传失败获取错误信息
                        echo $files["banner$i"]->getError();
                    }
                }
            }//end baner1/2/3轮播处理
            if(db('sys')->where('id','1')->update($data)){
                $this->redirect('index');
            }else{
                $this->error('修改错误,原因可能是信息相同');
            }
        }
        $sysData = db('sys')->select()[0];
        $SysObj = new SysModel();
        $cateRes = $SysObj->tree();

//        dump($cateRes);die;
//        $cateObj = new CateModel();
//        $cateRes = $cateObj->cateTree();
        $this->assign(array(
            'cateRes'=> $cateRes,
            'sysData' => $sysData,
            ));
        unset($cateObj);
        return view();
    }
}
