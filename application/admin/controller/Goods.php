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
        $db=Base::connect();
        $arr = $db->query("select goods_id, sum(kucun) as kucun1 from shuxing group by goods_id ");
      //  var_dump($arr);die;
        $this->assign("arr",$arr);
        return $this->fetch();
    }
    public function shuxing()
    {
        $id=input("get.id");
        $this->assign("id",$id);
        return $this->fetch();
    }
    public function shuxing1()
    {
        $id=input("get.id");
        $db=Base::connect();
        $arr = $db->query("select * from ecgoods join shuxing on ecgoods.goods_id=shuxing.goods_id where shuxing.goods_id=$id");
        echo json_encode($arr);
    }

    public function show1(){
        $db=Base::connect();
        $arr = $db->query("select * from category ");
        $js=$this->getTree($arr);

    }
    public function show2(){
        $db=Base::connect();
        $arr = $db->query("select * from brand ");
        echo json_encode($arr);
    }
    public function show3(){
        $db=Base::connect();
        $arr = $db->query("select * from category ");
        echo json_encode($arr);
    }
    public function addaction(){
        $db=Base::connect();
        $arr2 = $db->query("select * from brand ");
        $this->assign("brand",$arr2);
        return $this->fetch("addgoods");
    }
    public function detail(){
        $id=input("get.id");
        $this->assign("id",$id);
        return $this->fetch("detail");
    }
    private function getTree($arr,$pid = 0, $level = 0){
        static $list = [];
        foreach ($arr as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['parent_id'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $flg = str_repeat('|--',$level);
                // 更新 名称值
                $mid=$value['cat_id'];
                $value['cat_name'] = $flg.$value['cat_name'];
                // 输出 名称
//                    echo $value['name']."<br/>";
                echo "<option value='$mid'>".$value['cat_name']."</option>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($arr[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($arr, $value['cat_id'], $level+1);
            }
        }
        echo "</ul>";
        return $list;
    }



    public function show()
    {
        $db=Base::connect();
        $arr = $db->query("select * from ecgoods join category on ecgoods.cat_id=category.cat_id join brand on ecgoods.brand_id=brand.brand_id order by goods_id");
        echo json_encode($arr);
        die;
    }
    public function add(){
        $db=Base::connect();
        $data=input();
        $goods_name=$data['goods_name'];
        $goods_sn=$data['goods_sn'];
        $changecategory=$data['changecategory'];
        $changebrand=$data['changebrand'];
        $shop_price=$data['shop_price'];
        if(empty($goods_name)||empty($goods_sn)||empty($changecategory)||empty($changebrand)||empty($shop_price)){
            $js = ['code' => '7', 'status' => 'error', 'data' => "名称，货号，品牌，分类，市场价都不能为空"];
            echo json_encode($js);
            die;
        }
        $arr = $db->query("insert into ecgoods (`goods_name`,`goods_sn`,`brand_id`,`cat_id`, `shop_price`)values('$goods_name','$goods_sn','$changebrand','$changecategory','$shop_price')");
        $js = ['code' => '0', 'status' => 'ok', 'data' => "添加成功"];
        echo json_encode($js);
        die;
    }

    function addshuxing(){
        $data=input();
        $id= $data['id'];
        $shuxing1= $data['shuxing1'];
        $shuxing2= $data['shuxing2'];
        $shuxing3= $data['shuxing3'];
        $kucun= $data['kucun'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($shuxing1)&&empty($shuxing2)&&empty($shuxing3)){
            $arr = ['code' => '10', 'status' => 'error', 'data' => "属性不能全为空"];
            echo json_encode($arr);
            die;
        }
        if(empty($kucun) || (!is_numeric($kucun)) || ($kucun<0)){
            $arr = ['code' => '10', 'status' => 'error', 'data' => "库存必须为正整数"];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr2= $db->query("insert into shuxing(`goods_id`,`shuxing1`,`shuxing2`,`shuxing3`,`kucun`) values($id,'$shuxing1','$shuxing2','$shuxing3',$kucun)");

        $arr = ['code' => '0', 'status' => 'ok', 'data' => "添加成功"];
        echo json_encode($arr);
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
        $arr2= $db->query("delete from ecgoods where goods_id=$id");
        $arr3= $db->query("delete from goods_img where goods_id=$id");
        $arr4= $db->query("delete from shuxing where goods_id=$id");
        $arr = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
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

        $id1=implode($arr," or goods_id=");
        $db=Base::connect();
        $arr4 =$db->query("delete from ecgoods where goods_id=$id1");
        $arr3= $db->query("delete from goods_img where goods_id=$id1");
        $arr2= $db->query("delete from shuxing where goods_id=$id1");
        $arr1 = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr1);
    }

    public function pu_update(){
        $data= input();
        $id = $data['id'];
        $name = $data['name'];
        $sn = $data['sn'];
        $price = $data['price'];
        $cate = $data['cate'];
        $brand = $data['brand'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        if (empty($name)||empty($sn)||empty($price)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => "名称，价格，货号都不能为空"];
            echo json_encode($arr);
            die;
        }

        $db=Base::connect();
        $arr2= $db->query("select * from ecgoods where goods_name='$name' or goods_sn='$sn' ");
        if (empty($arr2) || !empty($arr2)&&$arr2[0]['goods_id']==$id) {
            $arr=$db->query("update ecgoods set goods_name='$name',goods_sn='$sn',shop_price='$price',brand_id='$brand',cat_id='$cate' where goods_id=$id");
            $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
            echo json_encode($arr5);
        } else{
            $arr3 = ['code' => '0', 'status' => 'error', 'data' => "品牌名或货号已存在"];
            echo json_encode($arr3);
        }
    }
//
//    public function add()
//    {
//
//        $data= input();
//        $name = $data['name'];
//        $description = $data['description'];
//        $site = $data['site'];
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($name)) {
//            $arr = ['code' => '3', 'status' => 'error', 'data' => "名称不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($description)) {
//            $arr = ['code' => '2', 'status' => 'error', 'data' => "评论不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        if (empty($site)) {
//            $arr = ['code' => '2', 'status' => 'error', 'data' => "网址不能为空"];
//            echo json_encode($arr);
//            die;
//        }
//        $db=Base::connect();
//        $logo = request()->file('logo');
//        // 移动到框架应用根目录/public/uploads/ 目录下
//        if($logo){
//            $info = $logo->move('./uploads');
//            if($info){
//                // 成功上传后 获取上传信息
//                $path = $info->getSaveName();
//                $path =substr_replace($path,"/",8,0);
//                $arr2= $db->query("select * from brand where brand_name='$name'");
//                if (empty($arr2)) {
//                    $arr=$db->query("insert into brand(`brand_name`,`brand_desc`,`site_url`,`brand_logo`) values('$name','$description','$site','$path')");
//                    $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
//                    echo json_encode($arr5);
//                } else{
//                    $arr3 = ['code' => '0', 'status' => 'error', 'data' => "品牌名已存在"];
//                    echo json_encode($arr3);
//                }
//                // echo $img_src; //返回ajax请求
//            }else{
//                // 上传失败获取错误信息
//                $this->error($logo->getError());
//            }
//        }else{
//            $arr5 = ['code' => '6', 'status' => 'error', 'data' => "文件不能为空"];
//            echo json_encode($arr5);
//        }
//    }
//
//    public function uplogo()
//    {
//
//        $data= input();
//        $id = $data['id'];
//
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//
//        $db=Base::connect();
//        $arr7=$db->query("select * from brand where brand_id=$id");
//
//        $pic = $arr7[0]['brand_logo'];
//        if(file_exists('./uploads/' . $pic)) {
//            unlink('./uploads/' . $pic);
//        }
//        $logo = request()->file('logo');
//        // 移动到框架应用根目录/public/uploads/ 目录下
//        if($logo){
//            $info = $logo->move('./uploads');
//            if($info){
//                // 成功上传后 获取上传信息
//                $path = $info->getSaveName();
//                $path =substr_replace($path,"/",8,0);
//                $arr=$db->query("update brand set brand_logo='$path' where brand_id=$id");
//                $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "修改成功"];
//                echo json_encode($arr5);
//                // echo $img_src; //返回ajax请求
//            }else{
//                // 上传失败获取错误信息
//                $this->error($logo->getError());
//            }
//        }else{
//            $arr5 = ['code' => '6', 'status' => 'error', 'data' => "文件不能为空"];
//            echo json_encode($arr5);
//        }
//    }
//
//
//    function delete(){
//        $data = input();
//        $id = input("post.id");
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $db=Base::connect();
//        $arr7=$db->query("select * from brand where brand_id=$id");
//
//        $pic=$arr7[0]['brand_logo'];
//        if(file_exists('./uploads/' . $pic)) {
//            unlink('./uploads/' . $pic);
//        }
//
//        $db=Base::connect();
//        $arr2= $db->query("delete from brand where brand_id=$id");
//
//        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
//        echo json_encode($arr);
//    }
//
//    function deleteMore(){
//        $data= input();
//        $id= $data['id'];
//        $validate = new \app\admin\validate\Delete;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $arr=explode(",",$id);
//        array_shift($arr);
//
//        $id1=implode($arr," or brand_id=");
////        $id2=implode($arr,",");
////        echo $id2;die;
//        //$id2=implode($arr," or role_id=");
//        $db=Base::connect();
//        $arr3 =$db->query("select * from brand where brand_id=$id1");
//        //   echo json_encode($arr3);die;
//        for ($i=0;$i<count($arr3);$i++){
//            $pic=$arr3[$i]['brand_logo'];
//            if(file_exists('./uploads/' . $pic)) {
//                unlink('./uploads/' . $pic);
//            }
//        }
//
//        $arr4 =$db->query("delete from brand where brand_id=$id1");
//        $arr1 = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
//        echo json_encode($arr1);
//    }
}


