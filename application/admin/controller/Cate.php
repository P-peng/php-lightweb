<?php
namespace app\admin\controller;

use app\admin\model\Cate as CateModel;

class Cate extends Common
{
    public function index()//lst清单
    {
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();//模型调用

        $this->assign('cateRes',$cateRes);
        unset($cateRes);
        unset($cateObj);
        return view('lst');
    }

    public function add(){
        $cateObj = new CateModel();
        if(request()->isPost()){
            $data = input('post.');
            if($data['pid'] != 0){//不是顶级栏目  栏目类型必须和父类栏目同意type
                //根据传入pid查询类型  然后和子类匹配
                $father = db('cate')->where('id',$data['pid'])->field('type')->find();
                if($father['type'] != $data['type']){
                    $this->error('添加栏目错误,原因可能是所添加的栏目和上一级栏目不是同一类型');
                }
            }
            $cateObj = new CateModel();
            if($cateObj->addCate($data)){
                $this->redirect('index');
            }else{
                $this->error('添加栏目失败');
            }
            unset($cateObj);
        }
        $cateRes = $cateObj->cateTree();
        $this->assign('cateRes',$cateRes);
        unset($cateObj);
        return view('add');
    }

    public function edit($id){
        $oneData = db('cate')->where('id',$id)->select();
        $cateObj = new CateModel();
        $cateRes = $cateObj->cateTree();
        foreach ($oneData as $k => $v){
            $oneData = $v;
        }
        $this->assign(array(
            'oneData' => $oneData,
            'cateRes' => $cateRes
        ));
        if(request()->isPost()){
            $data = input('post.');

            $sign = db('cate')->where('id',$data['id'])->update($data);
            //当此栏目type改变时候，所属的子栏目应该改为和父栏目同一类型

            $cateObj->upChilrenCateType($data['id'],$data['type']);

            if($sign !=0){
                $this->redirect('index');
            }else{
                $this->error('修改失败，原因可能是与原内容相同');
            }
        }
        unset($cateRes);
        unset($cateRes);
        unset($cateObj);
        return view('edit');
    }

    public function del($id){
        $idSet = [1,2,3,4,5,6];
        if(in_array($id,$idSet))
            $this->error('不可删除');
        $cateObj = new CateModel();
        if($cateObj->delTree($id)){
            $this->redirect('index');
        }else{
            $this->error('删除栏目失败');
        }

    }
}
