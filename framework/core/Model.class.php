<?php
namespace framework\core;
use framework\dao\DAOPDO;
class Model
{
    protected $dao;

    public function __construct()
    {
        $options = [
            'host' => 'localhost',
            'port' => '3306',
            'dbname' => 'user',
            'charset' => 'utf8',
            'user' => 'root',
            'password' => 'root'
        ];
        $this->dao = DAOPDO::getSingleTon($options);
    }
}