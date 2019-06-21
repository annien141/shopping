<?php
namespace app\admin\controller;
use Db;
use think\facade\Session;
session_start();
class index extends Common
{
    public function index()
    {
        $sql="select * from admin";
        $arr=Db::query($sql);
        return view('index',["arr"=>$arr]);
    }

    function logout(){
        unset($_SESSION['aname']);
        unset($_SESSION['aid']);
        $this->redirect("login/index");
    }
}
