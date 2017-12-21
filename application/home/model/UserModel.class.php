<?php
namespace home\model;
use framework\core\Model;
/*
 * 用户模型类，主要操作用户表的数据
 * 使用OOP思想描述数据表的话，一张表就认为是一个类，这个表的所有的操作（增删改查），封装到类的方法中
 *
 * */

class UserModel extends Model
{
    //添加一个用户
    public function user_add()
    {
        $sql = "insert into user ('name','phone') values ('sone','7731848350')";
        $this->dao->exec($sql);
    }

    //删除一个用户
    public function user_delete()
    {

    }

    //修改一个用户
    public function user_update()
    {

    }

    //查询一个用户
    public function user_select()
    {

    }
}