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
class Permission extends Common
{

    public function list1()
    {
        return $this->fetch();
    }

    public function admin_permission()
    {
        return $this->fetch();
    }

    public function admin_list()
    {
        return $this->fetch();
    }
    public function admin_role()
    {
        return $this->fetch();
    }


    public function show()
    {

        $rbac = new Rbac;
        $arr = $rbac->getPermissionCategory([['status', '=', 1]]);
        echo json_encode($arr);
    }

    public function add()
    {
        $data = input();
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        if (empty($data['name'])) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "分类名不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($data['commit'])) {
            $arr = ['code' => '2', 'status' => 'error', 'data' => "评论不能为空"];
            echo json_encode($arr);
            die;
        }
        $rbac = new Rbac;
        $getarr = $rbac->getPermissionCategory([['name', '=', $data['name']]]); //=select
        if (empty($getarr)) {
            $rbac->savePermissionCategory([   //=insert into
                'name' => $data['name'],
                'description' => $data['commit'],
                'status' => 1
            ]);
            $arr = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
        } else {
            $arr = ['code' => '0', 'status' => 'error', 'data' => "分类已存在"];  
        }
        echo json_encode($arr);
    }

    function delete(){
        $id = input("post.id");
        $rbac = new Rbac;
        $rbac->delPermissionCategory([   //=delete
            'id' => $id,
        ]);

        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr);
    }

    function deleteMore(){
        $id = input("post.id");
        $data= input();
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $arr=explode(",",$id);
        array_shift($arr);
        $rbac = new Rbac;
        $rbac->delPermissionCategory($arr);

        $arr1 = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr1);
    }

    function updateName(){
        $id = input("post.id");
        $name = input("post.name");
        $descript = input("post.descript");
        $data= input();

        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        if (empty($name)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "名称不能为空"];
            echo json_encode($arr);
            die;
        }
        $rbac = new Rbac;
        $getarr = $rbac->getPermissionCategory([['name', '=', $name]]);
        if (empty($getarr)) {
            $sql="update permission_category set name='$name' where id=$id";
            $arr2=Db::query($sql);
            $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
            echo json_encode($arr);
        } else {
            if($getarr[0]['id']==$id){
                $sql="update permission_category set name='$name' where id=$id";
                $arr2=Db::query($sql);
                $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
                echo json_encode($arr);
            } else{
                $arr = ['code' => '0', 'status' => 'error', 'data' => "分类已存在"];
                echo json_encode($arr);
            }

        }
    }
    function updateDescript(){
        $id = input("post.id");
        $name = input("post.name");
        $descript = input("post.descript");
        $data= input();
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "评论不能为空"];
            echo json_encode($arr);
            die;
        }

        $sql="update permission_category set description='$name' where id=$id";
        $arr2=Db::query($sql);
        $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
        echo json_encode($arr);
    }

    function pu_update(){
        $data= input();
        $id = input("post.id");
        $name = input("post.name");
        $description = input("post.description");
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "分类名不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($description)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "评论不能为空"];
            echo json_encode($arr);
            die;
        }

        $rbac = new Rbac;
        $getarr = $rbac->getPermissionCategory([['name', '=', $name]]);
        if (empty($getarr)) {
            $sql="update permission_category set name='$name',description='$description' where id=$id";
            $arr2=Db::query($sql);
            $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
            echo json_encode($arr);
        } else {
            if ($getarr[0]['id'] == $id) {
                $sql = "update permission_category set name='$name',description='$description' where id=$id";
                $arr2 = Db::query($sql);
                $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
                echo json_encode($arr);
            } else {
                $arr = ['code' => '0', 'status' => 'error', 'data' => "分类已存在"];
                echo json_encode($arr);
            }
        }

//        $sql="update permission_category set name='$name',description='$description' where id=$id";
//        $arr2=Db::query($sql);
//        $arr = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//        echo json_encode($arr);
    }

    public function admin(){
        $id=input('post.id');
        $arr=Db::table('permission_category')->where('id',$id)->select();
        $res=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($res);
    }
}


