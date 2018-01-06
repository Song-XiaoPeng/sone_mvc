<?php
namespace framework\core;
class Framework
{
    const TEST = 'test';//使用const可以在这里定义常量

    public function __construct()
    {
        //加载路径常量
        $this->initConst();
        //加载配置文件
        $frameworkConfig = $this->loadFrameworkConfig();
        $commonConfig = $this->loadCommonConfig();
        //引用全局作用域中可用的全部变量
        //一个包含了全部变量的全局组合数组。变量的名字就是数组的键
        $GLOBALS['config'] = array_merge($frameworkConfig, $commonConfig);
        //在初始化MCA的时候，需要设置默认的模块、控制器、方法
        $this->initMCA();
        $moduleConfig = $this->loadModuleConfig();
        //替换、合并配置文件
        $GLOBALS['config'] = array_merge($GLOBALS['config'], $moduleConfig);
        //思考？为什么不在这里一起合并
        //$config = array_merge($config1,$config2,$config3);
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

    //自定义自动加载函数
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

    //加载框架配置文件
    public function loadFrameworkConfig()
    {
        $config_file = FRAMEWORK_ROOT . "config/config.php";
        if (file_exists($config_file)) {//检查文件或目录是否存在 return bool
            return require $config_file; //require 和 include 几乎完全一样，除了处理失败的方式不同之外。require 在出错时产生 E_COMPILE_ERROR 级别的错误。换句话说将导致脚本中止而 include 只产生警告（E_WARNING），脚本会继续运行。
            //用法 include 文件名;
            //include 语句包含并运行指定文件。
            //当一个文件被包含时，其中所包含的代码继承了 include 所在行的变量范围。从该处开始，调用文件在该行处可用的任何变量在被调用的文件中也都可用。不过所有在包含文件中定义的函数和类都具有全局作用域。 被包含文件
            //如果 include 出现于调用文件中的一个函数里，则被调用的文件中所包含的所有代码将表现得如同它们是在该函数内部定义的一样。所以它将遵循该函数的变量范围。此规则的一个例外是魔术常量，它们是在发生包含之前就已被解析器处理的。
            //当一个文件被包含时，语法解析器在目标文件的开头脱离 PHP 模式并进入 HTML 模式，到文件结尾处恢复。由于此原因，目标文件中需要作为 PHP 代码执行的任何代码都必须被包括在有效的 PHP 起始和结束标记之中。
            //如果"URL fopen wrappers"在 PHP 中被激活（默认配置），可以用 URL（通过 HTTP 或者其它支持的封装协议——见支持的协议和封装协议）而不是本地文件来指定要被包含的文件。如果目标服务器将目标文件作为 PHP 代码解释，则可以用适用于 HTTP GET 的 URL 请求字符串来向被包括的文件传递变量。严格的说这和包含一个文件并继承父文件的变量空间并不是一回事；该脚本文件实际上已经在远程服务器上运行了，而本地脚本则包括了其结果。
        }
        return [];
    }

    //加载通用配置文件
    public function loadCommonConfig()
    {
        $config_file = APP_PATH . "common/config/config.php";
        if (file_exists($config_file)) {
            return require_once $config_file;//include_once 可以用于在脚本执行期间同一个文件有可能被包含超过一次的情况下，想确保它只被包含一次以避免函数重定义，变量重新赋值等问题。
        }
        return [];
    }

    //加载模块配置文件 ？ 因为程序访问的是具体的模块里面的控制器里面的某一个方法 module/controller/action,所以访问哪个模块就加载哪个模块的配置文件
    public function loadModuleConfig()
    {
        $config_file = APP_PATH . MODULE . "/config/config.php";
        if (file_exists($config_file)) {
            return require_once $config_file;
        }
        return [];
    }

    public function initMCA()
    {
        //前台还是后台
        $m = isset($_GET['m']) ? $_GET['m'] : $GLOBALS['config']['default_module'];
        define('MODULE', $m);
        //访问那个控制器
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];
        define('CONTROLLER', $c);
        //const CONTROLLER = 1; 不可以在这里使用const定义常量
        //访问控制器的哪个操作
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];
        define('ACTION', $a);
    }

    //初始化路径常量
    public function initConst()
    {
        define('ROOT_PATH', str_replace('\\', '/', getcwd() . '/'));
        define('APP_PATH', ROOT_PATH . 'application/');
        define('FRAMEWORK_ROOT', ROOT_PATH . 'framework/');
//        const APP_PATH = ROOT_PATH .'application/';
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