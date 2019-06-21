<?php
namespace app\admin\controller;
use Db;

class index extends \think\Controller
{
    public function index()
    {
        $sql="select * from admin";
        $arr=Db::query($sql);
        return view('index',["arr"=>$arr]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
