<?php
namespace framework\core;
class Framework
{
    const TEST = 'test';//使用const可以在这里定义常量
    public function __construct()
    {
        $this->initMCA();
        $this->autoload();
        $this->dispatch();
    }

    //注册自动加载
    public function autoload()
    {
        //说明：如果一个函数的参数是回掉函数，直接写函数的名字
        //如果函数的参数是一个对象，需要传一个数组进去，参数1：对象，参数2：对象的方法
        spl_autoload_register([$this, 'autoloader']);
    }

    public function autoloader($className)
    {
        //        echo '我们需要' . $className . '<br>';
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
        if (file_exists($class_file)) {
            require_once $class_file;
        }
    }

    public function initMCA()
    {
        //前台还是后台
        $m = isset($_GET['m']) ? $_GET['m'] : 'home';
        define('MODULE', $m);
        //访问那个控制器
        $c = isset($_GET['c']) ? $_GET['c'] : 'Index';
        define('CONTROLLER', $c);
        //const CONTROLLER = 1; 不可以在这里使用const定义常量
        //访问控制器的哪个操作
        $a = isset($_GET['a']) ? $_GET['a'] : 'indexAction';
        define('ACTION', $a);
    }

    public function dispatch()
    {
        $controller_name = MODULE . '\\controller\\' . CONTROLLER . 'Controller';
        //调用控制器的方法

        $a = ACTION;
        //先加载控制器类，再实例化对象

        $controller = new $controller_name;
        $controller->$a();
    }
}