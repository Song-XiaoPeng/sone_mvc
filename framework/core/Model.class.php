<?php
namespace framework\core;

use framework\dao\DAOPDO;

class Model
{
    protected $dao;
    public $logic_table = '';//逻辑层面的表名，可能不是真实的表名，但是可以通过该属性得到实际操作的表名

    public function __construct()
    {
        $options = $GLOBALS['config'];
        $this->dao = DAOPDO::getSingleTon($options);
    }

    /**
     *     封装insert方法
        $sql = "insert into table (id,name) values (1,2)";
     * @param $data
     * @return bool|int
     */
    public function insert($data)
    {
        $fields = array_keys($data);
        $data = array_values($data);
        $sql = "insert into " . $this->logic_table . " (" . implode(',', $fields) . ") values (" . implode(',', $data) . ")";
        $res = $this->dao->exec($sql);
        return $res;
    }

    /**
     * 封装update方法,更新一般都具有更新的条件
     * @param $data
     * @param $where where条件可以是个数组，也可以是个字符串。['id'=>1,'name'=>'sone']
     */
    public function update($data, $where)
    {
        $where =
        $sql = "update " . $this->logic_table . " set `name`=1 where id=1";
    }
}