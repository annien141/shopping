<?php
namespace app\index\controller;
use Db;

class Login extends \think\Controller
{
    public function index()
    {
        return view('login');
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
