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
        $aname=isset($_SESSION['aname'])?$_SESSION['aname']:"";
        if (empty($aname)){
            $this->redirect("login/index");
        }else{
            $this->assign('aname',$aname);
        }
    }
}