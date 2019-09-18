<?php

include_once "../../fn.php";

$id = $_GET['id'];

//准备sql语句删除
$sql = "delete from categories where id in ($id)";

//执行sql语句
my_exec( $sql );

?>