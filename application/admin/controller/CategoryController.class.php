<?php
namespace admin\controller;

use framework\core\Controller;

/**
 * 分类控制器类，主要负责分类管理这个模块
 * 通常我们在控制器类里面发布命令：命令模型处理数据、命令视图显示数据
 * Class CategoryController
 */
class CategoryController extends Controller
{
    //控制器的方法后面加上Action后缀，目的是为了避免和模型类里面的方法名重复，方便记忆
    //查询分类列表
    public function indexAction()
    {
        require_once "../Factory.class.php";
        //命令模型查询数据
        $model = Factory::M('CategoryModel');
        //查询分类列表
        $cat_list = $model->cat_select();
        //命令视图显示数据
        //使用smarty将分类列表的数据分配过去

        $this->smarty->assign('cat_list', $cat_list);
        $this->smarty->display('view/cat_list.html');
    }

    //删除分类
    public function deleteAction()
    {

    }

    //编辑分类
    public function editAction()
    {

    }

    //增加分类
    public function addAction()
    {

    }
}