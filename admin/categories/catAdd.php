<?php

include_once "../../fn.php";

$name = $_GET['name'];
$slug = $_GET['slug'];


$sql = "insert into categories (slug, name) values ('$slug', '$name')";
    //执行添加语句
  
$res = my_exec( $sql );

echo json_encode( $res );

?>