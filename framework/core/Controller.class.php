<?php
namespace framework\core;

/*
 * 控制器层的封装
 * 控制器层是根据功能模块划分的，便于分工协作开发，一个员工负责一个功能，功能又是一个控制器类
 * 将来项目会有很多功能模块，每个功能模块又是一个控制器类，每个控制器中都需要使用smarty进行数据的分配，所以我们将smarty的初始化再次封装到一个基础的控制器类中，其他的控制器再继承
 * */
class Controller
{
    protected $smarty;

    public function __construct()
    {
//        require_once "./framework/vendor/smarty/Smarty.class.php";
        $this->smarty = new \Smarty();
        $this->smarty->left_delimiter = '<{';
        $this->smarty->right_delimiter = '<{';
        $this->smarty->setCompileDir('view/tpl_c');
        $this->smarty->setTemplateDir('view/tpl');
    }
}