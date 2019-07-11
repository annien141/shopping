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
class Admin extends Common
{
    public function admin_list()
    {
        return $this->fetch("admin_list");
    }

    public function show()
    {
        $sql = "select u.id ,u.user_name,u.mobile,u.last_login_time,ur.user_id ,ur.role_id ,r.id as rid,r.name as rname,r.description from user as u join user_role as ur on ur.user_id=u.id join role as r on r.id=ur.role_id order by u.id";
        $arr = Db::query($sql);
        echo json_encode($arr);
    }
    
    public function show1(){
        $sql = "select * from role";
        $arr = Db::query($sql);
        echo json_encode($arr);
    }

    public function addaction()
    {
        return $this->fetch("addadmin");
    }

    public function add()
    {
        $data = input();
        $name = $data['name1'];
        $phone = $data['phone'];
        $time=$data['time'];
        $select=$data['select'];
        $password=md5($data['password']);
        $time=str_replace('T',' ',$time);
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }


        if (empty($name)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => "用户名不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($password)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "密码不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($phone)) {
            $phone="暂未添加";
        } else{
            $str = $phone;
            $isMatched = preg_match('/^1[3-8]{1}[0-9]{9}$/', $str, $matches);
            if($isMatched==0){
                $arr = ['code' => '5', 'status' => 'error', 'data' => "手机号格式错误"];
                echo json_encode($arr);
                die;
            }
        }
        if (empty($time)) {
            $arr = ['code' => '2', 'status' => 'error', 'data' => "登录时间不能为空"];
            echo json_encode($arr);
            die;
        } else{

        }

        $sql="select * from user where user_name='$name' or mobile='$phone'";
        $getarr =Db::query($sql);

        if (empty($getarr) || !empty($getarr)&&$getarr[0]['mobile']=='暂未添加') {
            $sql="insert into user(`user_name`,`mobile`,`last_login_time`,`password`)values('$name','$phone','$time','$password')";
            $arr1 =Db::query($sql);

            $sql1="select * from user where user_name='$name'";
            $arr2 =Db::query($sql1);
            $uid=$arr2[0]['id'];

            $sql2="insert into user_role(`user_id`,`role_id`)values('$uid','$select')";
            $arr3 =Db::query($sql2);

            $arr = ['code' => '0', 'status' => 'ok', 'data' => "添加成功"];
        } else {
            $arr = ['code' => '6', 'status' => 'error', 'data' => "用户名已存在"];
        }
        echo json_encode($arr);
    }

    function deletemore(){
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
        $id2=implode($arr," or user_id=");
        $arr3 =Db::query("delete from user where id=$id1");

        $arr4 =Db::query("delete from user_role where user_id=$id2");
        $arr1 = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr1);
    }
    function delete(){
        $id = input("post.id");
        $data = input();
        $validate = new \app\admin\validate\Delete();
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        $arr1 =Db::query("delete from user where id=$id");
        $arr2 =Db::query("delete from user_role where user_id=$id");

        $arr = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr);
    }

    function pu_update(){
        $data= input();
        $id = $data['id'];
        $name = $data['name'];
        $phone = $data['phone'];
        $password= md5($data['password']);
        $time= $data['time'];
        $time=str_replace('T',' ',$time);
        $select2= $data['select2'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => "用户名不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($password)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "密码不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($time)) {
            $arr = ['code' => '2', 'status' => 'error', 'data' => "登录时间不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($phone)) {
//            $arr = ['code' => '1', 'status' => 'error', 'data' => "手机号不能为空"];
//            echo json_encode($arr);
//            die;
            $phone="暂未添加";
        } else{
            $str = $phone;
            $isMatched = preg_match('/^1[3-8]{1}[0-9]{9}$/', $str, $matches);
            if($isMatched==0){
                $arr = ['code' => '5', 'status' => 'error', 'data' => "手机号格式错误"];
                echo json_encode($arr);
                die;
            }
        }


        $sql="select * from user where user_name='$name' or mobile='$phone'";
        $arr2=Db::query($sql);
        if (empty($arr2) || !empty($arr2)&&$arr2[0]['mobile']=='暂未添加') {
            // 启动事务
            Db::startTrans();
            try {
                $arr=Db::query("update user set user_name='$name',mobile='$phone',last_login_time='$time',password='$password' where id=$id");

                $arr=Db::query("update user_role set role_id='$select2' where user_id=$id");

                $arr5 = ['code' => '0', 'status' => 'ok', 'data' => "修改成功"];
                echo json_encode($arr5);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }


        }else{
            foreach ($arr2 as $key =>$value){
                if($value['id']!=$id) {
                    $arr3 = ['code' => '7', 'status' => 'error', 'data' => "用户名或手机号已存在"];
                    echo json_encode($arr3);
                    die;
                }
            }

            // 启动事务
            Db::startTrans();
            try {
                $arr=Db::query("update user set user_name='$name',mobile='$phone',last_login_time='$time',password='$password' where id=$id");

                $arr=Db::query("update user_role set role_id='$select2' where user_id=$id");

                $arr3 = ['code' => '0', 'status' => 'ok', 'data' => "修改成功"];
                echo json_encode($arr3);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        }
    }
}