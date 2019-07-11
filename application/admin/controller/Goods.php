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
class Goods extends Base
{

    public function goods()
    {
        return $this->fetch();
    }
    public function show()
    {
        $db=Base::connect();
        $arr = $db->query("select * from brand order by brand_id");
        echo json_encode($arr);
        die;
    }

    public function pu_update(){
        $data= input();
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $site = $data['site'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => "分类名不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($description)) {
            $arr = ['code' => '3', 'status' => 'error', 'data' => "评论不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($site)) {
            $arr = ['code' => '5', 'status' => 'error', 'data' => "网址不能为空"];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr2= $db->query("select * from brand where brand_name='$name'");
        if (empty($arr2) || !empty($arr2)&&$arr2[0]['brand_id']==$id) {
            $arr=$db->query("update brand set brand_name='$name',brand_desc='$description',site_url='$site' where brand_id=$id");
            $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
            echo json_encode($arr5);
        } else{
            $arr3 = ['code' => '0', 'status' => 'error', 'data' => "品牌名已存在"];
            echo json_encode($arr3);
        }
    }

    public function add()
    {

        $data= input();
        $name = $data['name'];
        $description = $data['description'];
        $site = $data['site'];
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
        if (empty($description)) {
            $arr = ['code' => '2', 'status' => 'error', 'data' => "评论不能为空"];
            echo json_encode($arr);
            die;
        }
        if (empty($site)) {
            $arr = ['code' => '2', 'status' => 'error', 'data' => "网址不能为空"];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $logo = request()->file('logo');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($logo){
            $info = $logo->move('./uploads');
            if($info){
                // 成功上传后 获取上传信息
                $path = $info->getSaveName();
                $path =substr_replace($path,"/",8,0);
                $arr2= $db->query("select * from brand where brand_name='$name'");
                if (empty($arr2)) {
                    $arr=$db->query("insert into brand(`brand_name`,`brand_desc`,`site_url`,`brand_logo`) values('$name','$description','$site','$path')");
                    $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
                    echo json_encode($arr5);
                } else{
                    $arr3 = ['code' => '0', 'status' => 'error', 'data' => "品牌名已存在"];
                    echo json_encode($arr3);
                }
                // echo $img_src; //返回ajax请求
            }else{
                // 上传失败获取错误信息
                $this->error($logo->getError());
            }
        }else{
            $arr5 = ['code' => '6', 'status' => 'error', 'data' => "文件不能为空"];
            echo json_encode($arr5);
        }
    }

    public function uplogo()
    {

        $data= input();
        $id = $data['id'];

        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        $db=Base::connect();
        $arr7=$db->query("select * from brand where brand_id=$id");

        $pic = $arr7[0]['brand_logo'];
        if(file_exists('./uploads/' . $pic)) {
            unlink('./uploads/' . $pic);
        }
        $logo = request()->file('logo');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($logo){
            $info = $logo->move('./uploads');
            if($info){
                // 成功上传后 获取上传信息
                $path = $info->getSaveName();
                $path =substr_replace($path,"/",8,0);
                $arr=$db->query("update brand set brand_logo='$path' where brand_id=$id");
                $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
                echo json_encode($arr5);
                // echo $img_src; //返回ajax请求
            }else{
                // 上传失败获取错误信息
                $this->error($logo->getError());
            }
        }else{
            $arr5 = ['code' => '6', 'status' => 'error', 'data' => "文件不能为空"];
            echo json_encode($arr5);
        }
    }


    function delete(){
        $data = input();
        $id = input("post.id");
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr7=$db->query("select * from brand where brand_id=$id");

        $pic=$arr7[0]['brand_logo'];
        if(file_exists('./uploads/' . $pic)) {
            unlink('./uploads/' . $pic);
        }

        $db=Base::connect();
        $arr2= $db->query("delete from brand where brand_id=$id");

        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr);
    }

    function deleteMore(){
        $data= input();
        $id= $data['id'];
        $validate = new \app\admin\validate\Delete;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $arr=explode(",",$id);
        array_shift($arr);

        $id1=implode($arr," or brand_id=");
//        $id2=implode($arr,",");
//        echo $id2;die;
        //$id2=implode($arr," or role_id=");
        $db=Base::connect();
        $arr3 =$db->query("select * from brand where brand_id=$id1");
        //   echo json_encode($arr3);die;
        for ($i=0;$i<count($arr3);$i++){
            $pic=$arr3[$i]['brand_logo'];
            if(file_exists('./uploads/' . $pic)) {
                unlink('./uploads/' . $pic);
            }
        }

        $arr4 =$db->query("delete from brand where brand_id=$id1");
        $arr1 = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr1);
    }
}


