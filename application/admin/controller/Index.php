<?php
namespace app\admin\controller;
use Db;
use gmars\rbac\Rbac;

use think\facade\Session;
session_start();
class index extends Common
{
    public function index()
    {
        $sql="select * from admin";
        $arr=Db::query($sql);
//        echo "123";
        return view('index',["arr"=>$arr]);
    }

    function logout(){
        unset($_SESSION['name']);
        $this->redirect("login/index");
    }
    public function rbac(){
        $rbac = new Rbac();
        $rbac->createTable();
    }
}
