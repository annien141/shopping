<?php
namespace app\admin\controller;
use Db;

class Login extends \think\Controller
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $name=input("get.name1");
//        var_dump($uname);die;
        $password=input("get.password1");
        $sql="select * from admin where aname='$name' and apassword='$password'";
        $arr=Db::query($sql);
        if(!empty($arr)){
            echo "123";
        }
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
