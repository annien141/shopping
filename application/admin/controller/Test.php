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
class Test extends Common
{
//    private function getChar($num)  // $num为生成汉字的数量
//    {
//        $b = '';
//        for ($i=0; $i<$num; $i++) {
//            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
//            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
//            // 转码
//            $b .= iconv('GB2312', 'UTF-8', $a);
//        }
//        return $b;
//    }

//     public function test(){
//         $arr=[];
//         for ($i=0;$i<10000;$i++){
//             $num=rand(3, 10);
//             $b=$this->getChar($num);
//             $arr[]=$b;
//         }
//         $arr1=implode($arr,"'),('");
//         $sql="insert into `test1`(`name`) values('$arr1')";
//         Db::query($sql);
//     }
       function test(){
           return $this->fetch();
       }
}