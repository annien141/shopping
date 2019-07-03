<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
use gmars\rbac\Rbac;
if (!session_id()) session_start();
class Control extends Common
{


    function pcAdd(){
        return $this->fetch();
    }
    function  savePermissionCategory(){
        $rbac=new Rbac;
        $rbac->savePermissionCategory([
            'name' => '商品管理',
            'description' => '商品管理1',
            'status' => 1
        ]);
    }
    function permissionAdd(){
        return $this->fetch();
    }
    function permissionAddAction(){
        $rbac=new Rbac;
        $rbac->createPermission([
            'name' => '品牌列表查询',
            'description' => '品牌列表查询',
            'status' => 1,
            'type' => 1,
            'category_id' => 1,
            'path' => 'admin/brand/list1',
        ]);
    }

    function  roleAdd(){
        return $this->fetch();
    }
    function  roleAddACtion(){
        $rbac=new Rbac;
        $rbac->createRole([
            'name' => '内容管理员',
            'description' => '负责网站内容管理',
            'status' => 1
        ], '1');
    }

    function  assignUserRole(){
        $rbac=new Rbac;
        $rbac->assignUserRole(1, [1]);
    }
}