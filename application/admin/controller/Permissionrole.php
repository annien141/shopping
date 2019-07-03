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
class Permissionrole extends Common
{


    public function admin_role()
    {
        return $this->fetch();
    }

    public function addrole(){
        return $this->fetch();
    }
    public function show()
    {
        $sql="select p.id,p.name,p.description,p.path,p.category_id,p_c.name as p_c_name from permission as p join permission_category as p_c on p.category_id=p_c.id order by id ";
        $arr=Db::query($sql);

        $newarr=[];
        foreach ($arr as $key=>$value){
            $newarr[$value['p_c_name']][]=$value;
        }
//        var_dump($newarr);die;
        echo json_encode($newarr);
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
        
        $sql="select * from permission where name='$name'";
        $getarr =Db::query($sql);
        if (empty($getarr)) {
//            $sql1="insert into permission(`name`,`path`,`description`,`category_id`,`status`) values ('$name','$path','$description','$select',1)";
//            $arr1 =Db::query($sql1);
            $arr = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
        } else {
            $arr = ['code' => '0', 'status' => 'error', 'data' => "角色已存在"];
        }
        echo json_encode($arr);
    }
//
//    function delete(){
//        $id = input("post.id");
//        $data = input();
//        $validate = new \app\admin\validate\Delete();
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $sql1="delete from  permission where id=$id";
//        $arr1 =Db::query($sql1);
//
//        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
//        echo json_encode($arr);
//    }
//
//    function deleteMore(){
//        $id = input("post.id");
//        $data= input();
//        $validate = new \app\admin\validate\Delete;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $arr=explode(",",$id);
//        array_shift($arr);
//        $arr2=implode($arr," or id=");
//        $sql1="delete from permission where id=$arr2";
//        $arr3 =Db::query($sql1);
//        $arr1 = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
//        echo json_encode($arr1);
//    }
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
//        if (empty($path)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "路径不能为空"];
//            echo json_encode($arr);
//            die;
//        }
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
//    function pu_update(){
//        $data= input();
//        $id = input("post.id");
//        $name = input("post.name");
//        $path = input("post.path");
//        $select = input("post.select2");
//        $description = input("post.description");
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($name)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "分类名不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($description)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "评论不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($path)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "路径不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        $sql="select * from permission where name='$name' or path='$path'";
//        $arr2=Db::query($sql);
//        if (empty($arr2)) {
//            $sql1="update permission set name='$name',description='$description',path='$path',category_id='$select' where id=$id";
//            $arr1=Db::query($sql1);
//            $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//            echo json_encode($arr);
//        }else{
//            foreach ($arr2 as $key =>$value){
//                if($value['id']!=$id) {
//                    $arr3 = ['code' => '0', 'status' => 'error', 'data' => "权限名或路径已存在"];
//                    echo json_encode($arr3);
//                    die;
//                }
//            }
//            $sql2="update permission set name='$name',description='$description',path='$path',category_id='$select' where id=$id";
//            $arr4=Db::query($sql2);
//            $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//            echo json_encode($arr5);
//        }
//    }
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
        $arr=Db::table('permission')->where('id',$id)->select();
        $res=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($res);
    }
}


