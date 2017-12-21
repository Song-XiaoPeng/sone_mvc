<?php
namespace framework\core;

class Factory
{
    //模型对象只需要实例化一个就够了,使用单例模式三四一公的方法比较繁琐，因此采用工厂模式去实例化一个单例对象
    //工厂模式实现单例模型对象
    public static function M($modelName)
    {
        static $model_list = [];
        if (!in_array($modelName, $model_list)) {
            $model_list[$modelName] = new $modelName;
        }
        return $model_list[$modelName];
    }
}