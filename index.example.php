<?php

/**
 * 入口文件
 * 分发控制器，用来接收用户请求时携带的参数
 */
//自动加载
spl_autoload_register('autoloader');
function autoloader($className)
{
    echo '我们需要' . $className .'<br>';
    //针对第三方类做一个特例处理
    if ($className == "Smarty") {
        require_once "./framework/vendor/smarty/Smarty.class.php";
        return;
    }
    //1.先将带有命名空间的类分隔开
    $arr = explode('\\', $className);
    /*
     * [
     *      'admin',
     *      'controller',
     *      'CategoryController'
     * ]
     * [
     *      'framework',
     *      'core|dao|tools',
     *      'Controller|I_DAO'
     * ]
     */
    //2.根据第一个元素确定加载的根目录
    if ($arr[0] == 'framework') {
        $basic_path = "./";
    } else {
        $basic_path = "./application/";
    }
    //3.确定application、framework里面的子目录
    $sub_path = str_replace('\\', '/', $className);// admin/controller/CategoryController

    //4.确定文件名
    //确定后缀，类文件 .class.php 接口 .interface.php
    //framework\dao\I_DAO
    if (substr($arr[count($arr) - 1], 0, 2) == "I_") {
        $fix = ".interface.php";
    } else {
        $fix = ".class.php";
    }
    //5.加载类
    //如果不是按照我们的命名空间的规则定义的，说明不是我们需要加载的类，不用加载
    $class_file = $basic_path . $sub_path . $fix;
    if(file_exists($class_file)){
        require_once $class_file;
    }
}

//前台还是后台
$m = isset($_GET['m']) ? $_GET['m'] : 'home';
//访问那个控制器
$c = isset($_GET['c']) ? $_GET['c'] : 'Index';
//访问控制器的哪个操作
$a = isset($_GET['a']) ? $_GET['a'] : 'indexAction';

//确定访问的那个控制器（带有命名空间）
$controller_name = $m . '\\controller\\' . $c . 'Controller';
//$class_file = $m . '/controller/' . $controller_name . '.class.php';


//先加载控制器，再实例化对象
$controller = new $controller_name;//IndexController

//$controller_name->$a();//IndexController->indexAction()  Call to a member function indexAction() on string

//调用控制器的方法
$controller->$a();