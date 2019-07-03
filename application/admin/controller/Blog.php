<?php
namespace app\admin\controller;

use app\admin\controller\Common;
use app\admin\model\Cate as CateModel;
class Blog extends Common
{
    public function index(){
        $data = db('blog')->paginate(10)->each(function ($item,$keys){
            $item['cateName'] = db('cate')->where('id',$item['cateid'])->field('cateName')->find()['cateName'];
            return $item;
        });
        $this->assign(array(
            'data' => $data,
        ));
        return view('lst');
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $file = request()->file('thumb');
            if(!empty($file)) {
                $info = $file->rule('date')->move('../public/uploads');
                if($info){
                    $shijian = date('Ymd');
                    $data['thumb'] = "public/uploads/".$shijian."/".$info->getFilename();//可改善,图片地址
                }
            }
            $data['time'] = date('M d,Y/h:i A');
            $sign = db('blog')->insert($data);
            if($sign>0){
                $this->redirect('index');
            }else{
                $this->error('添加错误');
            }
        }
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();
        $this->assign('cateRes',$cateRes);
        unset($cateRes);
        unset($cateObj);
        return view('add');
        return view();
    }
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $file = request()->file('thumb');
            if(!empty($data['wu'])){  //要删除图片
                $delF = db('blog')->where('id',$id)->field("thumb")->find();
                if(file_exists(ROOT_PATH.$delF["thumb"])&&$delF["thumb"]){//如果exist,则delete
                    unlink(ROOT_PATH.$delF["thumb"]);//unlink 可删除图片   可删除文件
                }
                unset($data['wu']);
                $data['thumb'] = null;
            } else if(!empty($file)){//要修改图片
                $info = $file->rule('date')->move('../public/uploads');
                if($info){
                    $shijian = date('Ymd');
                    $data['thumb'] = "public/uploads/".$shijian."/".$info->getFilename();//可改善,图片地址

                    $delF = db('blog')->where('id',$id)->field("thumb")->find();
                    if(file_exists(ROOT_PATH.$delF["thumb"])&&$delF["thumb"]){//如果exist,则delete
                        unlink(ROOT_PATH.$delF["thumb"]);//unlink 可删除图片   可删除文件
                    }
                }
            }
            if(db('blog')->where('id',$data['id'])->update($data)>0){
                $this->redirect('index');
            }else{
                $this->error('错误信息,原因可能没有做修改');
            }
        }
        $blogData = db('blog')->where('id',$id)->find();
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();
        $this->assign(array(
            'blog' => $blogData,
            'cateRes' => $cateRes
        ));
        unset($cateRes);
        unset($cateObj);
        return view();
    }
    public function del($id){
        if(!empty($id)){
            //图片存在则删除
            $delF = db('blog')->where('id',$id)->field("thumb")->find();
            if(file_exists(ROOT_PATH.$delF["thumb"])&&$delF["thumb"]){//如果exist,则delete
                unlink(ROOT_PATH.$delF["thumb"]);//unlink 可删除图片   可删除文件
            }
            $sign = db('blog')->delete($id);
            if($sign>0){
                $this->redirect('index');
            }else{
                $this->error('删除错误');
            }
        }else{
            $this->error('拦截机制，数据错误');
        }
        return view();
    }
}
