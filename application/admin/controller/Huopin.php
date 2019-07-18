<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
use gmars\rbac\Rbac;
use Request;
use app\admin\model\Brand as BrandModel;
if (!session_id()) session_start();
class Huopin extends Base
{

    public function huopin()
    {
        $id=input("get.id");
        $this->assign("id",$id);
        return $this->fetch("huopin");
    }

    public function show(){
        $id=input("get.id");
        $db=Base::connect();
        $arr1=$db->query("select * from shuxing where goods_id=$id");
       // var_dump($arr1);die;
        $shuxing2=[];
        $shuxing3=[];
        foreach ($arr1 as $key=>$value){
            $shuxing2[]=$value['shuxing2'];
            $shuxing3[]=$value['shuxing3'];
        }
        var_dump($shuxing3);die;
        $this->assign("arr1",$arr1);

        $arr=$db->query("select * from huopin join ecgoods where ecgoods.goods_id=huopin.goods_id");
        echo json_encode($arr);
        die;
    }
//    public function show()
//    {
//        $sql="select p.id,p.name,p.description,p.path,p.category_id,p_c.name as p_c_name from permission as p join permission_category as p_c on p.category_id=p_c.id order by id ";
//        $arr=Db::query($sql);
//        $newarr=[];
//        foreach ($arr as $key=>$value){
//            $newarr[$value['p_c_name']][]=$value;
//        }
//        echo json_encode($newarr);
//    }

    public function showrole()
    {
        $sql="select * from role";
        $arr=Db::query($sql);
        echo json_encode($arr);
    }
    public function showupdate()
    {
        $id=input("post.id");
        $sql="select role_permission.permission_id from role_permission join role on role.id=role_permission.role_id where role.id=$id";
        $arr=Db::query($sql);

        echo json_encode($arr);
    }

    public function add()
    {
        $data = input();
        $name = $data['name'];
        $description = $data['description'];
        $permission_id=$data['permission_id'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        if (empty($data['name'])) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "角色不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($permission_id)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "权限不能为空"];
            echo json_encode($arr);
            die;
        }
        $rabc=new Rbac();
        $sql="select * from role where name='$name'";
        $getarr =Db::query($sql);

        $arr=explode(",",$permission_id);
        array_shift($arr);
        $permission_id=implode(",",$arr);
        if (empty($getarr)) {
            $rabc->createRole([
                'name'=>$name,
                'description'=>$description,
                'status'=>1
            ],$permission_id);
            $arr = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
        } else {
            $arr = ['code' => '0', 'status' => 'error', 'data' => "角色已存在"];
        }
        echo json_encode($arr);
    }
//
    function delete(){
        $id = input("post.id");
        $data = input();
        $validate = new \app\admin\validate\Delete();
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        $arr1 =Db::query("delete from role where id=$id");
        $arr2 =Db::query("delete from role_permission where role_id=$id");

        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr);
    }
//
    function deleteMore(){
        $id = input("post.id");
        $data= input();
        $validate = new \app\admin\validate\Delete;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $arr=explode(",",$id);
        array_shift($arr);
        $id1=implode($arr," or id=");
        $id2=implode($arr," or role_id=");
        $arr3 =Db::query("delete from role where id=$id1");

        $arr4 =Db::query("delete from role_permission where role_id=$id2");
        $arr1 = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr1);
    }
//
//    function updateName(){
//        $id = input("post.id");
//        $name = input("post.name");
//        $descript = input("post.descript");
//        $data= input();
//
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//
//        if (empty($name)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "权限名不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        $sql="select * from permission where name='$name'";
//        $getarr =Db::query($sql);
//        if (empty($getarr)) {
//            $sql="update permission set name='$name' where id=$id";
//            $arr2=Db::query($sql);
//            $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//            echo json_encode($arr);
//        } else {
//            if($getarr[0]['id']==$id){
//                $sql="update permission set name='$name' where id=$id";
//                $arr2=Db::query($sql);
//                $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//                echo json_encode($arr);
//            } else{
//                $arr = ['code' => '0', 'status' => 'error', 'data' => "权限已存在"];
//                echo json_encode($arr);
//            }
//
//        }
//    }
//    function updateDescript(){
//        $id = input("post.id");
//        $name = input("post.name");
//        $descript = input("post.descript");
//        $data= input();
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($name)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "评论不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//
//        $sql="update permission_category set description='$name' where id=$id";
//        $arr2=Db::query($sql);
//        $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//        echo json_encode($arr);
//    }
//
//
//    function updatePath(){
//        $id = input("post.id");
//        $path = input("post.path");
//        $descript = input("post.descript");
//        $data= input();
//
//        $validate = new \app\admin\validate\Path;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//
//
//        $sql="select * from permission where path='$path'";
//        $getarr =Db::query($sql);
//        if (empty($getarr)) {
//            $sql="update permission set path='$path' where id=$id";
//            $arr2=Db::query($sql);
//            $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//            echo json_encode($arr);
//        } else {
//            if($getarr[0]['id']==$id){
//                $sql="update permission set path='$path' where id=$id";
//                $arr2=Db::query($sql);
//                $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//                echo json_encode($arr);
//            } else{
//                $arr = ['code' => '0', 'status' => 'error', 'data' => "路径已存在"];
//                echo json_encode($arr);
//            }
//
//        }
//    }
//
    function pu_update(){
        $data= input();
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $permission_id = $data['permission_id'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "角色不能为空"];
            echo json_encode($arr);
            die;
        }

        $sql="select * from role where name='$name'";
        $arr2=Db::query($sql);
        if (empty($arr2) || !empty($arr2)&&$arr2[0]['id']==$id) {
            $arr=Db::query("update role set name='$name',description='$description' where id=$id");

            $arr3=Db::query("delete from role_permission where role_id=$id");

            $arr6=explode(",",$permission_id);
            array_shift($arr6);
            foreach ($arr6 as $key=>$value) {
                $arr=Db::query("insert into role_permission(`role_id`,`permission_id`) values($id,'$value')");
            }
            $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
            echo json_encode($arr5);
        }else{
            $arr3 = ['code' => '0', 'status' => 'error', 'data' => "角色名已存在"];
            echo json_encode($arr3);

        }
    }
//
//    public function updatefenlei(){
//        $id = input("post.id");
//        $oldname = input("post.oldname");
//        $name = input("post.name");
//        $data= input();
//
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $sql = "update permission set category_id='$name' where id=$id";
//        $arr2 = Db::query($sql);
//        $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//        echo json_encode($arr);
//
//    }

    public function admin(){
        $id=input('post.id');
        $arr=Db::table('role')->where('id',$id)->select();
        $res=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($res);
    }
}


