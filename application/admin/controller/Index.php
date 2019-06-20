<?php
namespace app\admin\controller;
use Db;

class index extends \think\Controller
{
    public function index()
    {
        $sql="select * from user";
        $arr=Db::query($sql);
        echo 678;
        var_dump($arr);
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
