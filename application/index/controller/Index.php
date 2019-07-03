<?php
namespace app\index\controller;


class Index extends Common
{
    public function index()//默认Get方法
    {

        //start 系统推荐1/2数据
        $mainId = db('sys')->where('id','1')->field('mainid1,mainid2,mainid3')->find();
        $mainId1DataName = db('cate')->where('id',$mainId['mainid1'])->field('cateName,a')->find();
        $mainId2DataName = db('cate')->where('id',$mainId['mainid2'])->field('cateName,a')->find();
        $mainId3DataName = db('cate')->where('id',$mainId['mainid3'])->field('cateName,a')->find();

        $mainId1DataName['id'] = $mainId['mainid1'];
        $mainId2DataName['id'] = $mainId['mainid2'];
        $mainId3DataName['id'] = $mainId['mainid3'];

        $mainId1Data = db('product')->where('cateid',$mainId['mainid1'])->limit('20')->order('sort desc')->select();
        $mainId2Data = db('product')->where('cateid',$mainId['mainid2'])->limit('8')->order('sort desc')->select();
        $mainId3Data = db('product')->where('cateid',$mainId['mainid3'])->limit('8')->order('sort desc')->select();

//        dump($mainId1DataName);
//        dump($mainId1Data);
//        dump($mainId2Data);
//        dump($mainId2DataName);die;

//        dump($mainId1DataName);
//        dump($mainId2DataName);die;
        $this->assign(array(
            'mainId1DataName' => $mainId1DataName,
            'mainId2DataName' => $mainId2DataName,
            'mainId3DataName' => $mainId3DataName,
            'mainId1Data' => $mainId1Data,
            'mainId2Data' => $mainId2Data,
            'mainId3Data' => $mainId3Data,
        ));//end  系统推荐1/2数据

        return view('home');
    }
}
