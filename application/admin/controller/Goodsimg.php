<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
//use think\Image;
use gmars\rbac\Rbac;
use Request;
use app\admin\model\Brand as BrandModel;
if (!session_id()) session_start();
class Goodsimg extends Base
{
    public function show(){
        $id=input("post.id");
        $db=Base::connect();
        $arr = $db->query("select * from goods_img where goods_id=$id");
        echo json_encode($arr);
        die;
    }
    public function add(){
        $id=input("post.id");
        $db=Base::connect();

        $files=Request::file("file");
        if(empty($files)){
            $arr5 = ['code' => '1', 'status' => 'error', 'data' => "至少上传1张"];
            echo json_encode($arr5);
            die;
        }                                                           
        foreach($files as $file){
            $info=$file->validate(['size'=>1024*1024,'ext'=>'gif,jpg,png,jpeg'])->move("./uploads/goods_img");

            if($info){
                // 成功上传后 获取上传信息
                $name = $info->getFilename();
                $data=date("Ymd");
                $path="$data/$name";

                $img=\think\Image::open("./uploads/goods_img/$path");

                $img_big="$data/big_$name";
                $img_middle="$data/middle_$name";
                $img_small="$data/small_$name";

                $img->thumb(150,150)->save("./uploads/goods_img/".$img_big);
                $img->thumb(100,100)->save("./uploads/goods_img/".$img_middle);
                $img->thumb(50,50)->save("./uploads/goods_img/".$img_small);


                $arr=$db->query("insert into goods_img(`big_img`,`middle_img`,`small_img`,`origin_img`,`goods_id`)values('$img_big','$img_middle','$img_small','$path','$id')");
                // echo $img_src; //返回ajax请求
            }else{
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }
        $arr5 = ['code' => '0', 'status' => 'ok', 'data' => "添加成功"];
        echo json_encode($arr5);
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
        $arr7=$db->query("select * from goods_img where id=$id");

        $big=$arr7[0]['big_img'];
        $middle=$arr7[0]['middle_img'];
        $small=$arr7[0]['small_img'];
        $origin= $arr7[0]['origin_img'];
        if(file_exists('./uploads/goods_img/' . $big)) {
            unlink('./uploads/goods_img/' . $big);
        }
        if(file_exists('./uploads/goods_img/' . $middle)) {
            unlink('./uploads/goods_img/' . $middle);
        }
        if(file_exists('./uploads/goods_img/' . $small)) {
            unlink('./uploads/goods_img/' . $small);
        }
        if(file_exists('./uploads/goods_img/' . $origin)) {
            unlink('./uploads/goods_img/' . $origin);
        }
        $db=Base::connect();
        $arr2= $db->query("delete from goods_img where id=$id");

        $arr = ['code' => '1', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($arr);
    }
}


