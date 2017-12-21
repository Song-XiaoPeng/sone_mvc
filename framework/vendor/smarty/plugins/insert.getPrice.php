<?php
//获得实时的商品价格
//$param变量会接收调用时传递的所有参数
function smarty_insert_getPrice($param)
{
    //echo '<pre>';
    // var_dump($param);
    $id = $param['id'];
    //由于该文件会被加载到10.goods.html里面
    global $dao;
    $sql = "SELECT shop_price FROM goods WHERE goods_id=$id";
    return $dao -> fetchColumn($sql);
}