<?php
namespace framework\core;

use framework\dao\DAOPDO;

class Model
{
    protected $dao;

    public function __construct()
    {
        $options = $GLOBALS['config'];
        $this->dao = DAOPDO::getSingleTon($options);
    }
}