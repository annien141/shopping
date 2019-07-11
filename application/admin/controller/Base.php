<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\facade\Session;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Base extends Controller
{
    public function initialize()
    {
        $name=Session::get('name');
        if (empty($name)){
            $this->redirect("login/index");
        }else{
            $this->assign('name',$name);
        }
    }

    public function connect()
    {
        $db = Db::connect([
            'type' => 'mysql',
            'hostname' => '47.102.45.98',
            'database' => 'zhangkaimeng',
            'username' => 'root',
            'password' => 'zhangkaimeng777!',
            'hostport' => '',
            'charset' => 'utf8',
        ]);
        return $db;
    }

    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $acc=["token"=>$token];
        echo $b=json_encode($acc);
    }
}
