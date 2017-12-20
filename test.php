<?php
require "DAOPDO.class.php";

$options = [
    'dbname' => 'mvc',
    'user' => 'root',
    'password' => 'root',
    'host' => 'localhost',
    'charset' => 'utf8',
    'port' => 3306
];
/*
 *
 create table mvc (
    `id` int auto_increment primary key,
    `key` varchar(255) not null default '' ,
    `value` varchar(255) default ''
 );

 insert into mvc (`key`,`value`) values ('xing','sone'),('ming','xiaop');
*/
$pdo = DAOPDO::getSingleTon($options);

$dao = $pdo->getPDO();
$sql = "select * from mvc";
$pdo_statement = $dao->prepare($sql);
$pdo_statement1 = $dao->prepare($sql);
$pdo_statement->execute();
$pdo_statement1->execute();
$res = $pdo_statement->fetch(PDO::FETCH_ASSOC);
echo '<pre>';
var_dump($res);
//$pdo_statement->closeCursor();
$res1 = $pdo_statement1->fetch(PDO::FETCH_ASSOC);
var_dump($res1);

die;
foreach ($pdo_statement as $v) {
    echo $v['key'];
}


echo '<pre>';
$sql = "select * from mvc";
var_dump($pdo->fetchAll($sql));
echo '<hr>';
var_dump($pdo->fetchRow($sql));
echo '<hr>';
var_dump($pdo->fetchRow($sql));

$sql = "select `key` from mvc";

echo '<hr>';
var_dump($pdo->fetchColumn($sql));
$sql = "insert mvc (`key`,`value`) values ('sss','ppp')";
echo '<hr>';
var_dump($pdo->exec($sql));
echo $pdo->lastInsertId();
