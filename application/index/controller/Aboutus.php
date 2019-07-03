<?php
namespace app\index\controller;
use app\index\model\Aboutus as aboutusModel;
class Aboutus extends Common
{
    public function index($id){
        $aboutusObj = new aboutusModel();
        $data = $aboutusObj->getAllData($id);
        $this->assign('aboutsData',$data);
        return view('aboutus');
    }
}
