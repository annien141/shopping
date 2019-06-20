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

    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 456;
        return 'hello,' . $name;
    }
}
