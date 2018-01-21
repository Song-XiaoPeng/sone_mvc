<?php
namespace framework\core;

use framework\dao\DAOPDO;

class Model
{
    protected $dao;
    public $logic_table = '';//逻辑层面的表名，可能不是真实的表名，但是可以通过该属性得到实际操作的表名
    public $true_table;
    public $prefix;
    protected $pk;

    public function __construct()
    {
        $this->initDao();
        $this->initTrueTable();
    }

    //初始化dao对象
    public function initDao()
    {
        $options = $GLOBALS['config'];
        $this->dao = DAOPDO::getSingleTon($options);
    }

    //初始化真实表名
    public function initTrueTable()
    {
        $this->true_table = '`' . $this->prefix . $this->logic_table . '`';
    }

    //初始化数据表的字段
    public function initField()
    {
        $sql = "DESC $this->true_table";
        $result = $this->pdo->fetchAll($sql);
        //遍历二维数组
        foreach ($result as $k => $value) {
            if ($value['Key'] == 'PRI') {
                $this->pk = $value['Field'];
            }
        }
    }

    /**
     *     封装insert方法
     * $sql = "insert into table (`id`,`name`) values (1,2)";
     * @param $data
     * ['id'=>1,'name'=>'sone']
     * @return bool|int
     */
    public function insert($data)
    {
        $sql = "insert into $this->true_table ";
        //1.拼接字段列表部分
        $fields = array_keys($data);
        $fields = array_map(function ($v) {
            return '`' . $v . '`';
        }, $fields);
        //数组元素拼成字符串
        $fields_str = '(' . implode(',', $fields) . ') ';
        $sql .= $fields_str;
        //2.拼接values部分
        $field_values = array_values($data);
        //安全处理，插入数据前，进行转义并包裹
        $field_values = array_map([$this->dao, "quote"], $field_values);
        $field_values = "VALUES (" . implode(',', $field_values) . ")";
        //拼接values
        $sql .= $field_values;
        //执行sql语句，返回自增id
        $this->dao->exec($sql);
        return $this->dao->lastInsertId();
    }

    /**
     * 自动删除操作
     * 实现DELETE FROM 表名 WHERE 主键字段=主键值
     * $model->delete(1)
     * delete from table where id=1;//@TODO 增加where条件类型
     * @param $where mixed
     * string 'id=1'
     * array ['id'=>1]
     */
    public function delete($id)
    {
        $sql = "DELETE FROM $this->true_table WHERE $this->pk=$id";
        return $this->dao->exec($sql);
    }

    /**
     * 自动更新操作
     * 封装update方法,更新一般都具有更新的条件
     * 要求：一定要有where条件，否则不允许更新
     *
     * UPDATE TABLE SET `id`="1",`name`="sone" where `字段名`="字段值"
     * @param $data
     * @param $where where条件可以是个数组，也可以是个字符串。['id'=>1,'name'=>'sone']
     */
    public function update($data, $where)
    {
        //没有条件不让更新
        if ($where == false) {
            return false;
        } else {
            $where_arr = array_map(function ($v, $k) {
                return '`' . $k . '`=' . $v;
            }, $where);
            $where_str = "WHERE ".implode($where_arr,' AND ');
        }

        $sql = "UPDATE $this->true_table SET ";
        $fields = array_keys($data);
        $fields = array_map(function ($v) {
            return '`' . $v . '`';
        }, $fields);

        $field_values = array_values($data);
        $field_values = array_map([$this->dao, 'quote'], $field_values);

        $str = '';
        foreach ($fields as $k => $v) {
            $str .= $v . '=' . $field_values[$k] . ',';
        }

        $str = substr($str, 0, -1);
        $sql .= $str . $where_str;

        return $this->dao->exec($sql);
    }
}