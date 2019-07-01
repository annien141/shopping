<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
if (!session_id()) session_start();
class Common extends Controller
{
    function initialize(){
        $name=isset($_SESSION['name'])?$_SESSION['name']:"";
        if (empty($name)){
            $this->redirect("login/index");
        }else{
            $this->assign('name',$name);
        }
    }
}