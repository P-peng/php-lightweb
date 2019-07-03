<?php
namespace app\admin\controller;

use app\admin\controller\Common;
use app\admin\model\Cate as CateModel;
use app\admin\model\Article as ArticleModel;
class Article extends Common
{
    public function index()//lst清单
    {
        $data = db('article')->alias('a')->join('cate c','a.cateid = c.id')->field('a.id,a.title,a.sort,a.time,a.content,c.cateName')->paginate('8');
//        foreach ($data as $k => $v){
//            foreach ($v as $k2 => $v2){
//            $data[$k2]['type'] = implode("",db('cate')->where('id',$data[$k2]['cateid'])->field('cateName')->find());
//            }
//        }

        $this->assign('data',$data);
        return view('lst');
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
           $data['time'] = date('Y/M/d/h/i/s/a');
           if(db('article')->insert($data)>0){
               echo "<script>if(!alert(\"增加成功\"))window.location.href =\"index\";</script>";
           }else{
               echo "<script>if(!alert(\"增加失败\")){window.location.href =\"add\"}</script>";
           }
        }

        $artObj = new ArticleModel();
        $cateRes = $artObj->getCata();

        $this->assign('cateRes',$cateRes);
        unset($cateRes);
        unset($cateObj);
        return view('add');
    }

    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            if(db('article')->update($data)>0){
                $this->redirect('index', '', 5, '页面跳转中...');
            }else{
                $this->error('修改失败');
            }
        }
        $oneData = db('Article')->where('id',$id)->select();

        foreach ($oneData as $k => $v){
            $oneData = $v;
        }
        $artObj = new ArticleModel();
        $cateRes = $artObj->getCata();
//dump($cateRes);die;
        $this->assign(array(
            'oneData' => $oneData,
            'cateRes' => $cateRes
        ));
        return view('edit');
    }

    public function del($id){
        if(db('article')->delete($id)>0){
            $this->redirect('index',2,3,'12312');
        }else{
            $this->error('删除不成功');
        }
    }
}
