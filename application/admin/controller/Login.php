<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use think\Db;
use think\db\Query;
use think\facade\Session;
session_start();
class Login
{
    public function index()
    {
        Session::clear();
        return view('login');
    }

    public function code(){
        $code=input("post.code");
        //$_SESSION['code']=$code;
        echo Session::set('code',$code);
        //echo $_SESSION['code'];
    }

    public function login()
    {
        $name=input("post.name1");
        $password=input("post.password1");
        $code=input("post.code");
        $password=md5($password);

        $code=strtoupper($code);
        $ret=strtoupper(Session::get('code'));
        if($code!=$ret ){
            $arr1=["code"=>"3","status"=>"error","message"=>"验证码错误!!!"];
            $type=json_encode($arr1);
            echo $type;
            die;
        }
        $sql="select * from user where user_name='$name' and password='$password'";
        $arr=Db::query($sql);
        if(!empty($arr)){
            Session::set('name',$name);
            $arr1=["code"=>"1","status"=>"ok","message"=>"登录成功"];
            $rbac=new Rbac;
            $rbac->cachePermission($arr[0]["id"]);
        }else{
            $arr1=["code"=>"2","status"=>"error","message"=>"用户名密码错误"];
        }
        $type=json_encode($arr1);
        echo $type;
    }

}
