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
            $arr1=["code"=>"1","status"=>"ok","message"=>"登录成功"];
            $type=json_encode($arr1);
            echo $type;
        }else{
            $arr1=["code"=>"2","status"=>"error","message"=>"用户名密码错误"];
            $type=json_encode($arr1);
            echo $type;
        }
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
