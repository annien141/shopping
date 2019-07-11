<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\facade\Session;
use think\Controller;
use Request;
use gmars\rbac\Rbac;
class Common extends Controller
{
    function initialize(){
        //$name=isset($_SESSION['name'])?$_SESSION['name']:"";
        $name=Session::get('name');
        if (empty($name)){
            $this->redirect("login/index");
        }else{
            $this->assign('name',$name);
        }
        
        //验证是否有权限
        $module=Request::module();
        $class=Request::controller();
        $action=Request::action();
        $arr_class=['Permission','Permissionlist','Permissionrole','Admin'];
        $arr_action=['show','delete','add','deleteMore','pu_update','delete','updateName','updateDescription','updatePath'];

        if (in_array($class,$arr_class)){
            if (in_array($action,$arr_action)){
                //echo 1111;exit;
                $str="$module/$class/$action";
                //echo $str;exit;
                $str=strtolower($str);
                $rbac=new Rbac;
                $bool=$rbac->can($str);
                //var_dump($str);var_dump($bool);die;
                if(!$bool){
                    header("Content-Type:application/json");
                    $arr=['code'=>'10001','status'=>'error','data'=>'没有权限'];
                    echo json_encode($arr);
                    die;
                }
            }
        }
    }
    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $acc=["token"=>$token];
        echo $b=json_encode($acc);
    }

}