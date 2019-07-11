<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
use gmars\rbac\Rbac;
use Request;
use app\admin\model\Brand as BrandModel;
class Brandfen extends Base
{
    public function brandfen()
    {
        return $this->fetch();
    }

    public function show()
    {
        $db = Base::connect();
        $arr = $db->table('category')->select();
        $this->getTree($arr);
    }

    private function getTree($arr,$pid = 0, $level = 0){
        static $list = [];
        echo "<ul style='cursor: pointer'>";
        foreach ($arr as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['parent_id'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
//                    $flg = str_repeat('|--',$level);
                // 更新 名称值
                $mid=$value['cat_id'];
                $value['cat_name'] = $value['cat_name'];
                // 输出 名称
//                    echo $value['name']."<br/>";
                echo "<li value='$mid'>".$value['cat_name']."</li>";
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

    function add(){
         $data=input();
         $name=$data['name'];
         $pid=$data['pid'];
         $validate = new \app\admin\validate\Permission;
         if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
         }

        $db=Base::connect();
         $arr=$db->query("insert into category(`cat_name`,`parent_id`) values('$name','$pid')");
        $arr5 = ['code' => '1', 'status' => 'ok', 'data' => "添加成功"];
        echo json_encode($arr5);
    }

//    function delete(){
//        $data=input();
//        $pid=$data['pid'];
//        $validate = new \app\admin\validate\Permission;
//        if (!$validate->check($data)) {
//            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
//            echo json_encode($arr);
//            die;
//        }
//        $db=Base::connect();
//        $arr=$db->query("select * from category where parent_id=$pid");
//        if(empty($arr)){
//            $arr2=$db->query("delete from category where cat_id=$pid");
//            $arr1 = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
//        } else{
//            $arr1 = ['code' => '1', 'status' => 'error', 'data' => "有小分类不能删除"];
//        }
//        echo json_encode($arr1);
//    }
    function delete(){
        $data=input();
        $pid=$data['pid'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr=$db->query("delete from category where cat_id=$pid");
        $js=$this->getChild($pid);
        $js = ['code' => '0', 'status' => 'ok', 'data' => "删除成功"];
        echo json_encode($js);
    }
    function getChild($id){
        $db=Base::connect();
        $arr=$db->query("select * from category where parent_id=$id");
        if(!empty($arr)){
            foreach($arr as $key=>$value){
                $cid=$value['cat_id'];
                $arr=$db->query("delete from category where cat_id=$cid");
                $this->getChild($value['cat_id']);
            }
        }
    }

    function update(){
        $data=input();
        $cat_name=$data['cat_name'];
        $cat_id=$data['cat_id'];
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $arr = ['code' => '4', 'status' => 'error', 'data' => $validate->getError()];
            echo json_encode($arr);
            die;
        }
        $db=Base::connect();
        $arr=$db->query("update category set cat_name='$cat_name' where cat_id=$cat_id");
        $js = ['code' => '0', 'status' => 'ok', 'data' => "修改成功"];
        echo json_encode($js);
    }
}
