<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
use gmars\rbac\Rbac;
use Request;
use app\admin\model\Brand as BrandModel;
use think\facade\Cache;
if (!session_id()) session_start();
class Goods extends Base
{

    public function goods()
    {
//        $db=Base::connect();
//        $arr = $db->query("select goods_id, sum(kucun) as kucun1 from shuxing group by goods_id ");
//      //  var_dump($arr);die;
//        $this->assign("arr",$arr);
        return $this->fetch();
    }

    public function shuxing()
    {
        $id=input("get.id");
        $db=Base::connect();

        $arr1=$db->query("select * from ecgoods where goods_id=$id");
        $fenlei=$arr1[0]['fenlei'];
        if (empty($fenlei)){
            $fenlei=0;
        }

        $attr_cate = $db->query("select * from attr_cate");
        $this->assign("id",$id);
        $this->assign("fenlei",$fenlei);
        $this->assign("attr_cate",$attr_cate);
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
        $redis=new \redis();
        $redis->connect('127.0.0.1',6379);
        $select=input("post.select");
    //    var_dump($select);die;
        $db=Base::connect();
        if(empty($select)){
            $arr = $db->query("select * from ecgoods join category on ecgoods.cat_id=category.cat_id join brand on ecgoods.brand_id=brand.brand_id order by goods_id");
            $arr2=$redis->ZREVRANGE("key101",0, -1);
            //    var_dump($arr2);die;
            if(count($arr2)<=3){
                $k=count($arr2);
            } else{
                $k=3;
            }
            $a='';
            for($i=0;$i<=$k;$i++){
                $a=$a.$arr2[$i].",";
            }
            $arr1=["data"=>$arr,"data1"=>$a];
            echo json_encode($arr1);
        } else{
            $redis->hSetnx("key1","$select",0);
            $redis->hIncrBy("key1","$select",1);
            $count=$redis->hGet("key1","$select");
          //  var_dump($count,$select);die;
            $arr3=$redis->zAdd("key101","$count","$select");
            $arr2=$redis->ZREVRANGE("key101",0, -1);
        //    var_dump($arr2);die;
            if(count($arr2)<=3){
                $k=count($arr2);
            } else{
                $k=3;
            }
            $a='';
            for($i=0;$i<=$k;$i++){
               $a=$a.$arr2[$i].",";
            }
            if($count<5){
                $arr = $db->query("select * from ecgoods join category on ecgoods.cat_id=category.cat_id join brand on ecgoods.brand_id=brand.brand_id where ecgoods.goods_name like('%$select%') order by goods_id");
                $arr1=["data"=>$arr,"data1"=>$a];
                echo json_encode($arr1);
            }else if($count==5){
                $arr = $db->query("select * from ecgoods join category on ecgoods.cat_id=category.cat_id join brand on ecgoods.brand_id=brand.brand_id where ecgoods.goods_name like('%$select%') order by goods_id");
                Cache::set("$select",$arr);  
                $arr1=["data"=>$arr,"data1"=>$a];
                echo json_encode($arr1);
            }else{
                $arr=Cache::get("$select");
                $arr1=["data"=>$arr,"data1"=>$a];
                echo json_encode($arr1);
            }
        }

    }
//    public function add(){
//        $db=Base::connect();
//        $data=input();
//        $id=$data['id'];
//        $attr_id=$data['attr_id'];
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $arr=explode(",",$attr_id);
//        array_shift($arr);
//        var_dump($arr);die;
//        $arr1 = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
//        echo json_encode($arr1);
//        die;
//    }
    function add1(){
        $db=Base::connect();
        $data=input();
        $id=input("post.id");
        $shuxing1=input("post.shuxing1");
        $arr=input("post.arr");
        $arr=array_unique($arr);
        array_pop($arr);
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $arr1 = $db->query("select * from shuxing where goods_id='$id'");
        if(empty($arr1)) {
            $arr1 = $db->query("update ecgoods set fenlei='$shuxing1' where goods_id=$id");
            $count = 0;
            foreach ($arr as $key => $value) {
                $shuxing2 = substr($value, 0, strpos($value, ':'));
                $shuxing3 = substr($value, strpos($value, ':') + 1);

                $arr1 = $db->query("select * from shuxing where goods_id='$id' and shuxing2='$shuxing2' and shuxing3='$shuxing3'");
                if (empty($arr1)) {
                    $arr3 = $db->query("insert into shuxing (`goods_id`,`shuxing2`,`shuxing3`) values('$id','$shuxing2','$shuxing3')");
                    $count++;
                }
            }
            $arr4 = ['code' => '0', 'status' => 'ok', 'data' => "添加了分类！添加了".$count."条数据"];
            echo json_encode($arr4);
        }else{
            $arr1 = $db->query("update ecgoods set fenlei='$shuxing1' where goods_id=$id");
            $count = 0;
            foreach ($arr as $key => $value) {
                $shuxing2 = substr($value, 0, strpos($value, ':'));
                $shuxing3 = substr($value, strpos($value, ':') + 1);

                $arr1 = $db->query("select * from shuxing where goods_id='$id' and shuxing2='$shuxing2' and shuxing3='$shuxing3'");
                if (empty($arr1)) {
                    $arr3 = $db->query("insert into shuxing (`goods_id`,`shuxing2`,`shuxing3`) values('$id','$shuxing2','$shuxing3')");
                    $count++;
                }
            }
            $arr4 = ['code' => '0', 'status' => 'warn', 'data' => "添加了".$count."条数据"];
            echo json_encode($arr4);
        }
       // var_dump($shuxing2,$shuxing3);die;




//        $arr = $db->query("select * from shuxing where goods_id='$id' and shuxing2='$shuxing4' and shuxing3='$shuxing5'");
//        if(empty($arr)){
//            $arr = $db->query("insert into shuxing (`goods_id`,`shuxing2`,`shuxing3`) values('$id','$shuxing2','$shuxing3')");
//            $arr3 = ['code' => '0', 'status' => 'ok', 'data' => "添加成功"];
//            echo json_encode($arr3);
//        }else{
//            $arr3 = ['code' => '12', 'status' => 'error', 'data' => "属性已存在"];
//            echo json_encode($arr3);
//        }

        
    }

    function addshuxing(){
        $data=input();
        $arr=input("post.arr");
        $id=input("post.id");
        $arr=array_unique($arr);
        array_pop($arr);
        var_dump($arr);die;
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr2= $db->query("delete from ecgoods where goods_id=$id");
    }

    function delete(){
        $data = input();
        $id = input("post.id");
        $goods_id = input("post.id1");
     //   var_dump($goods_id);die;
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }

        $db=Base::connect();
        $arr4= $db->query("delete from shuxing where sid=$id");

        $arr5= $db->query("select * from shuxing where goods_id=$goods_id");
       // var_dump($arr5);die;
        if(empty($arr5)){
            $arr6= $db->query("update ecgoods set fenlei=0 where goods_id=$goods_id");
            $arr = ['code' => '15', 'status' => 'warn', 'data' => "删除最后一条分类"];
            echo json_encode($arr);
            die;
        }
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

    function select11(){
        $id=input("post.id");
        $db=Base::connect();
        $attr = $db->query("select * from attr where attrcate_id=$id");
        echo json_encode($attr);
    }
    function select12(){
        $id=input("post.id");
        $db=Base::connect();
        $attr = $db->query("select * from attr_details where attr_id=$id");
        echo json_encode($attr);
    }
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


