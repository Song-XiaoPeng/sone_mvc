<?php
namespace framework\dao;
Interface I_DAO
{
    //查询一条记录的方法
    public function fetchRow($sql);

    //查询全部数据的方法
    public function fetchAll($sql);

    //查询一个字段的值
    public function fetchColumn($sql);

    //执行增删改的操作
    public function exec($sql);

    //引号转义包裹的方法
    public function quote($sql);

    //查询刚刚插入的这条数据的主键
    public function lastInsertId($key = '');
}