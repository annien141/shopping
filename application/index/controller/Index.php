<?php
namespace app\index\controller;
use Db;

class Index
{
    public function index()
    {
        $sql="select * from user";
        $arr=Db::query($sql);
        echo 123;
        var_dump($arr);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
