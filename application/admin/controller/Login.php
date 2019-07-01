<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use think\Db;
use think\db\Query;
use think\Session;
session_start();
class Login
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $name=input("post.name1");
        $password=input("post.password1");
        $code=input("post.code");
        $password=md5($password);

        $code2=$_COOKIE['AdminCode'];
        $ret = '';
        $len = strlen($code2);
        for ($i = 0; $i < $len; $i++){
            if ($code2[$i] == '%' && $code2[$i+1] == 'u'){
                $val = hexdec(substr($code2, $i+2, 4));
                if ($val < 0x7f) $ret .= chr($val);
                else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
                else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
                $i += 5;
            }
            else if ($code2[$i] == '%'){
                $ret .= urldecode(substr($code2, $i, 3));
                $i += 2;
            }
            else $ret .= $code2[$i];
        }
        $code=strtoupper($code);
        $ret=strtoupper($ret);
        if($code!=$ret){
            $arr1=["code"=>"3","status"=>"error","message"=>"验证码错误!!!"];
            $type=json_encode($arr1);
            echo $type;
            die;
        }
        $sql="select * from user where user_name='$name' and password='$password'";
        $arr=Db::query($sql);
        if(!empty($arr)){
            $_SESSION['name']=$name;
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
