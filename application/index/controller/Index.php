<?php
namespace app\index\controller;
use Db;

class index extends \think\Controller
{
    public function index()
    {
        $sql="select * from user";
        $arr=Db::query($sql);
        echo "189";
        return view('index',["arr"=>$arr]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
